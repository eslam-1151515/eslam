<?php

namespace App\Services\Shipping\Drivers;

use App\Contracts\ShippingProviderInterface;
use App\Models\Order;
use App\Models\ShippingGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JntShippingDriver implements ShippingProviderInterface
{
    /**
     * Create shipment order with J&T Express Egypt Open Platform (open.jtjms-eg.com)
     */
    public function createShipment(Order $order, ShippingGateway $gateway, array $options = []): array
    {
        $creds = $gateway->credentials ?? [];
        $apiAccount = $creds['api_account'] ?? $creds['api_key'] ?? $creds['api_password'] ?? null;
        $privateKey = $creds['private_key'] ?? null;
        $customerCode = $creds['customer_code'] ?? 'TEST';
        $isSandbox = (bool) ($creds['is_sandbox'] ?? false);

        if (empty($apiAccount) || empty($privateKey)) {
            return [
                'success' => false,
                'error'   => 'بيانات الربط لشركة J&T Express غير مكتملة (apiAccount / privateKey مطلوبين).',
            ];
        }

        $baseUrl = $isSandbox 
            ? 'https://demoopenapi.jtjms-eg.com/webopenplatformapi/api' 
            : 'https://openapi.jtjms-eg.com/webopenplatformapi/api';

        $url = "{$baseUrl}/order/addOrder";

        // Determine Store/Sender information
        $senderName = \App\Models\Setting::get('site_name', 'Order Saif Store');
        $senderPhone = \App\Models\Setting::get('contact_phone', '01015660731');
        $senderAddress = \App\Models\Setting::get('store_address', 'القاهرة - مصر');
        $senderCity = 'القاهرة';
        $senderProv = 'القاهرة';

        // Clean and format recipient details
        $receiverPhone = preg_replace('/[\s\+\-]/', '', $order->customer_phone);
        if (str_starts_with($receiverPhone, '201')) {
            $receiverPhone = '0' . substr($receiverPhone, 2);
        }

        // governorate might be a string column or an object relation
        $receiverProv = 'القاهرة';
        if ($order->governorate) {
            if (is_string($order->governorate)) {
                $receiverProv = $order->governorate;
            } elseif (is_object($order->governorate) && isset($order->governorate->name)) {
                $receiverProv = $order->governorate->name;
            }
        }
        $receiverAddress = $order->customer_address ?: ($order->shipping_address ?: $receiverProv);
        $receiverCity = $receiverProv;

        // Build items array - items is a JSON column (array), not an Eloquent relation
        $items = [];
        $orderItems = is_string($order->items) ? json_decode($order->items, true) : (array)($order->items ?? []);
        if (!empty($orderItems)) {
            foreach ($orderItems as $item) {
                $items[] = [
                    'itemName'  => $item['name'] ?? ($item['product_name'] ?? 'منتج'),
                    'number'    => (int) ($item['quantity'] ?? 1),
                    'itemValue' => (float) ($item['price'] ?? $order->total),
                    'itemType'  => 'ITN1',
                ];
            }
        } else {
            $items[] = [
                'itemName'  => "طلب رقم #{$order->reference_number}",
                'number'    => 1,
                'itemValue' => (float) $order->total,
                'itemType'  => 'ITN1',
            ];
        }

        $txlogisticId = "ORD_{$order->id}_{$order->reference_number}";

        $password = $creds['password'] ?? $creds['customer_password'] ?? 'nZ1wCm@1';
        $pwd = strtoupper(md5($password . 'jadada236t2'));
        $bodyDigest = base64_encode(md5($customerCode . $pwd . $privateKey, true));

        $payload = [
            'customerCode'  => $customerCode,
            'digest'        => $bodyDigest,
            'serviceType'   => '01',
            'orderType'     => '1',
            'deliveryType'  => '04',
            'operateType'   => '1',
            'txlogisticId'  => $txlogisticId,
            'goodsType'     => 'ITN1',
            'expressType'   => 'EZ',
            'payType'       => $order->payment_method === 'online' ? 'FREIGHT_PREPAID' : 'PP_PM',
            'priceCurrency' => 'EGP',
            'totalQuantity' => max(1, (int) array_sum(array_column($orderItems, 'quantity'))),
            'weight'        => 1.0,
            'itemsValue'    => (float) $order->total,
            'remark'        => $order->notes ?: '',
            'sender'        => [
                'name'        => $senderName,
                'mobile'      => $senderPhone,
                'phone'       => $senderPhone,
                'countryCode' => 'EGY',
                'prov'        => $senderProv,
                'city'        => $senderCity,
                'area'        => $senderCity,
                'street'      => $senderAddress,
                'address'     => $senderAddress,
            ],
            'receiver'      => [
                'name'        => $order->customer_name,
                'mobile'      => $receiverPhone,
                'phone'       => $receiverPhone,
                'countryCode' => 'EGY',
                'prov'        => $receiverProv,
                'city'        => $receiverCity,
                'area'        => $receiverCity,
                'street'      => $receiverAddress,
                'address'     => $receiverAddress,
            ],
            'items'         => $items,
        ];

        $bizContent = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $timestamp = (string) round(microtime(true) * 1000);
        $headerDigest = base64_encode(md5($bizContent . $privateKey, true));

        try {
            $response = Http::asForm()
                ->withHeaders([
                    'apiAccount'   => $apiAccount,
                    'digest'       => $headerDigest,
                    'timestamp'    => $timestamp,
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
                ])
                ->timeout(20)
                ->post($url, [
                    'bizContent' => $bizContent,
                ]);

            $resData = $response->json();

            if ($response->successful() && (
                ($resData['code'] ?? '') === '1' ||
                ($resData['succ'] ?? false) === true ||
                !empty($resData['data']['billCode'])
            )) {
                $dataNode = $resData['data'] ?? [];
                $trackingNumber = $dataNode['billCode'] ?? ($dataNode['txlogisticId'] ?? $txlogisticId);
                $sortingCode = $dataNode['sortingCode'] ?? ($dataNode['filterResult'] ?? '');

                return [
                    'success'         => true,
                    'tracking_number' => $trackingNumber,
                    'airway_bill_url' => "https://www.jtjms-eg.com/track?bills={$trackingNumber}",
                    'status'          => 'created',
                    'cost'            => (float) ($order->shipping_cost ?? 60.0),
                    'sorting_code'    => $sortingCode,
                    'raw_response'    => $resData,
                ];
            }

            $errorMsg = $resData['msg'] ?? ($resData['message'] ?? 'فشل إنشاء الشحنة مع J&T Express');
            Log::warning("J&T Express Shipment Creation Error for Order #{$order->id}: " . json_encode($resData, JSON_UNESCAPED_UNICODE));

            return [
                'success' => false,
                'error'   => "خطأ من J&T Express: {$errorMsg}",
                'raw'     => $resData,
            ];
        } catch (\Throwable $e) {
            Log::error("J&T Express Shipment Exception for Order #{$order->id}: " . $e->getMessage());
            return [
                'success' => false,
                'error'   => "حدث خطأ أثناء الاتصال بسيرفر J&T Express: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Track shipment status from J&T Express Open Platform
     */
    public function trackShipment(string $trackingNumber, ShippingGateway $gateway): array
    {
        $creds = $gateway->credentials ?? [];
        $apiAccount = $creds['api_account'] ?? $creds['api_key'] ?? null;
        $privateKey = $creds['private_key'] ?? null;
        $customerCode = $creds['customer_code'] ?? 'TEST';
        $isSandbox = (bool) ($creds['is_sandbox'] ?? false);

        if (empty($apiAccount) || empty($privateKey)) {
            return [
                'tracking_number' => $trackingNumber,
                'status'          => 'in_transit',
                'events'          => [],
            ];
        }

        $baseUrl = $isSandbox 
            ? 'https://demoopenapi.jtjms-eg.com/webopenplatformapi/api' 
            : 'https://openapi.jtjms-eg.com/webopenplatformapi/api';

        $url = "{$baseUrl}/logistics/trace";

        $payload = [
            'customerCode' => $customerCode,
            'billCodes'    => $trackingNumber,
        ];

        $bizContent = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $timestamp = (string) round(microtime(true) * 1000);
        $digest = base64_encode(pack("H*", md5($bizContent . $privateKey)));

        try {
            $response = Http::asForm()
                ->withHeaders([
                    'apiAccount'   => $apiAccount,
                    'digest'       => $digest,
                    'timestamp'    => $timestamp,
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
                ])
                ->timeout(15)
                ->post($url, [
                    'bizContent' => $bizContent,
                ]);

            $resData = $response->json();
            $events = [];
            $latestStatus = 'in_transit';

            if (!empty($resData['data'][0]['details'])) {
                foreach ($resData['data'][0]['details'] as $item) {
                    $events[] = [
                        'status'      => $item['scanType'] ?? 'UPDATE',
                        'time'        => $item['scanTime'] ?? now()->toIso8601String(),
                        'description' => $item['desc'] ?? '',
                        'location'    => $item['scandata'] ?? '',
                    ];
                }
            }

            return [
                'tracking_number' => $trackingNumber,
                'status'          => $latestStatus,
                'events'          => $events,
            ];
        } catch (\Throwable $e) {
            return [
                'tracking_number' => $trackingNumber,
                'status'          => 'in_transit',
                'events'          => [],
            ];
        }
    }

    /**
     * Cancel shipment on J&T Express
     */
    public function cancelShipment(string $trackingNumber, ShippingGateway $gateway): bool
    {
        $creds = $gateway->credentials ?? [];
        $apiAccount = $creds['api_account'] ?? null;
        $privateKey = $creds['private_key'] ?? null;
        $customerCode = $creds['customer_code'] ?? 'TEST';
        $isSandbox = (bool) ($creds['is_sandbox'] ?? false);

        if (empty($apiAccount) || empty($privateKey)) {
            return false;
        }

        $baseUrl = $isSandbox 
            ? 'https://demoopenapi.jtjms-eg.com/webopenplatformapi/api' 
            : 'https://openapi.jtjms-eg.com/webopenplatformapi/api';

        $url = "{$baseUrl}/order/cancelOrder";

        $password = $creds['password'] ?? $creds['customer_password'] ?? 'nZ1wCm@1';
        $pwd = strtoupper(md5($password . 'jadada236t2'));
        $bodyDigest = base64_encode(md5($customerCode . $pwd . $privateKey, true));

        $payload = [
            'customerCode' => $customerCode,
            'digest'       => $bodyDigest,
            'orderType'    => 1,
            'billCode'     => $trackingNumber,
            'reason'       => 'Cancelled by merchant in OrderSaif',
        ];

        $bizContent = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $timestamp = (string) round(microtime(true) * 1000);
        $headerDigest = base64_encode(md5($bizContent . $privateKey, true));

        try {
            $response = Http::asForm()
                ->withHeaders([
                    'apiAccount'   => $apiAccount,
                    'digest'       => $headerDigest,
                    'timestamp'    => $timestamp,
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
                ])
                ->timeout(15)
                ->post($url, [
                    'bizContent' => $bizContent,
                ]);

            $resData = $response->json();
            return (($resData['code'] ?? '') === '1' || ($resData['succ'] ?? false) === true);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
