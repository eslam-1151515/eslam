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

        // Determine Store/Sender information dynamically from merchant settings
        $tenantId = $order->tenant_id;
        $tenant = $order->tenant ?? \App\Models\Tenant::find($tenantId);

        // 1. Sender Name from merchant settings ('store_name') or tenant name
        $senderName = \App\Models\Setting::get('store_name', null, $tenantId)
            ?: ($tenant?->name ?: 'متجر');

        // 2. Sender Phone from merchant settings ('phone' - رقم الهاتف للاتصال)
        $senderPhoneRaw = \App\Models\Setting::get('phone', null, $tenantId);
        if (empty($senderPhoneRaw)) {
            $senderPhoneRaw = \App\Models\Setting::get('whatsapp', null, $tenantId) ?: ($tenant?->phone ?: '01033398191');
        }
        $senderPhone = preg_replace('/[\s\+\-]/', '', (string) $senderPhoneRaw);
        if (str_starts_with($senderPhone, '201')) {
            $senderPhone = '0' . substr($senderPhone, 2);
        }

        // 3. Sender Address from merchant settings ('address')
        $senderAddress = \App\Models\Setting::get('address', null, $tenantId);
        $senderCity = 'مدينة طلخا';
        $senderProv = 'الدقهلية';

        if (!empty($senderAddress)) {
            $egGovs = ['القاهرة', 'الجيزة', 'الإسكندرية', 'الدقهلية', 'البحيرة', 'الشرقية', 'الغربية', 'المنوفية', 'القليوبية', 'كفر الشيخ', 'دمياط', 'بورسعيد', 'الإسماعيلية', 'السويس', 'شمال سيناء', 'جنوب سيناء', 'بني سويف', 'المنيا', 'أسيوط', 'سوهاج', 'قنا', 'الأقصر', 'أسوان', 'البحر الأحمر', 'الوادي الجديد', 'مطروح', 'الفيوم'];
            foreach ($egGovs as $gov) {
                if (str_contains($senderAddress, $gov)) {
                    $senderProv = $gov;
                    $senderCity = $gov;
                    break;
                }
            }
        } else {
            $senderAddress = 'شارع معتصم، مدينة طلخا، الدقهلية';
        }

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

        // Build rich remark: product options/specs + real customer notes (excluding WhatsApp logs)
        $remarkParts = [];
        if (!empty($orderItems)) {
            $itemSpecs = [];
            foreach ($orderItems as $item) {
                $specs = [];
                if (!empty($item['options']) && is_array($item['options'])) {
                    foreach ($item['options'] as $optKey => $optVal) {
                        if (!empty($optVal)) {
                            $specs[] = "{$optKey}: {$optVal}";
                        }
                    }
                }
                if (!empty($item['selectedColor'])) {
                    $specs[] = "اللون: {$item['selectedColor']}";
                }
                if (!empty($item['selectedSize'])) {
                    $specs[] = "المقاس: {$item['selectedSize']}";
                }

                if (!empty($specs)) {
                    $specsStr = implode(' | ', $specs);
                    $itemName = $item['name'] ?? ($item['product_name'] ?? '');
                    if (count($orderItems) > 1 && $itemName) {
                        $itemSpecs[] = "{$itemName} ({$specsStr})";
                    } else {
                        $itemSpecs[] = $specsStr;
                    }
                }
            }
            if (!empty($itemSpecs)) {
                $remarkParts[] = implode(' - ', $itemSpecs);
            }
        }

        if (!empty($order->notes)) {
            $cleanNotes = [];
            $lines = explode("\n", (string) $order->notes);
            foreach ($lines as $line) {
                $lineTrim = trim($line);
                if ($lineTrim === '') continue;
                if (preg_match('/\[واتساب\]|\[whatsapp\]|بواسطة الواتس/ui', $lineTrim)) {
                    continue;
                }
                $cleanNotes[] = $lineTrim;
            }
            if (!empty($cleanNotes)) {
                $remarkParts[] = "ملاحظات: " . implode(' - ', $cleanNotes);
            }
        }

        $remark = !empty($remarkParts) ? implode(" | ", $remarkParts) : '';

        $payload = [
            'customerCode'  => $customerCode,
            'digest'        => $bodyDigest,
            'serviceType'   => '01',
            'orderType'     => '2',
            'deliveryType'  => '04',
            'operateType'   => '1',
            'txlogisticId'  => $txlogisticId,
            'goodsType'     => 'ITN1',
            'expressType'   => 'EZ',
            'payType'       => $order->payment_method === 'online' ? 'FREIGHT_PREPAID' : 'PP_PM',
            'priceCurrency' => 'EGP',
            'totalQuantity' => 1,
            'weight'        => 1.0,
            'itemsValue'    => (float) $order->total,
            'remark'        => $remark,
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

        $txlogisticId = '';
        $shipment = \App\Models\Shipment::where('tracking_number', $trackingNumber)->first();
        if ($shipment && $shipment->order) {
            $txlogisticId = "ORD_{$shipment->order->id}_{$shipment->order->reference_number}";
        }

        $payload = [
            'customerCode' => $customerCode,
            'digest'       => $bodyDigest,
            'orderType'    => 1,
            'txlogisticId' => $txlogisticId,
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
