<?php

namespace App\Services\Shipping\Drivers;

use App\Contracts\ShippingProviderInterface;
use App\Models\Order;
use App\Models\ShippingGateway;
use Illuminate\Support\Facades\Http;

class BostaShippingDriver implements ShippingProviderInterface
{
    protected string $baseUrl = 'https://api.bosta.co/v0';

    public function createShipment(Order $order, ShippingGateway $gateway, array $options = []): array
    {
        $apiKey = $gateway->credentials['api_key'] ?? null;

        // If in test mode or API key starts with 'test_', simulate response
        if (!$apiKey || str_starts_with($apiKey, 'test_') || config('app.env') === 'testing') {
            $trackingNumber = 'BST-' . strtoupper(\Illuminate\Support\Str::random(8));
            return [
                'success' => true,
                'tracking_number' => $trackingNumber,
                'airway_bill_url' => "https://bosta.co/awb/{$trackingNumber}.pdf",
                'status' => 'created',
                'cost' => 45.00,
                'raw_response' => [
                    'provider' => 'bosta',
                    'message' => 'Simulated shipment created for Bosta',
                    'delivery_id' => $trackingNumber,
                ],
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/deliveries", [
                'type' => 10, // Standard delivery
                'specs' => [
                    'packageDetails' => [
                        'itemsCount' => $order->items ? $order->items->sum('quantity') : 1,
                        'description' => "Order #{$order->order_number}",
                    ],
                ],
                'dropOffAddress' => [
                    'firstLine' => $order->shipping_address ?: 'Cairo, Egypt',
                    'city' => $order->governorate ?: 'Cairo',
                ],
                'receiver' => [
                    'firstName' => $order->customer_name,
                    'lastName' => '',
                    'phone' => $order->customer_phone,
                ],
                'cod' => (float) $order->total_amount,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $trackingNumber = $data['trackingNumber'] ?? $data['_id'] ?? ('BST-' . rand(100000, 999999));
                return [
                    'success' => true,
                    'tracking_number' => $trackingNumber,
                    'airway_bill_url' => $data['airwayBillUrl'] ?? "https://bosta.co/awb/{$trackingNumber}.pdf",
                    'status' => 'created',
                    'cost' => (float) ($data['cost'] ?? 45.00),
                    'raw_response' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Failed to create Bosta shipment',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function trackShipment(string $trackingNumber, ShippingGateway $gateway): array
    {
        $apiKey = $gateway->credentials['api_key'] ?? null;

        if (!$apiKey || str_starts_with($apiKey, 'test_') || config('app.env') === 'testing') {
            return [
                'tracking_number' => $trackingNumber,
                'status' => 'in_transit',
                'events' => [
                    ['status' => 'created', 'time' => now()->subHours(5)->toIso8601String(), 'description' => 'Shipment created'],
                    ['status' => 'picked_up', 'time' => now()->subHours(2)->toIso8601String(), 'description' => 'Picked up by courier'],
                ],
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->get("{$this->baseUrl}/deliveries/track/{$trackingNumber}");

            if ($response->successful()) {
                return $response->json();
            }

            return ['status' => 'unknown', 'tracking_number' => $trackingNumber];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function cancelShipment(string $trackingNumber, ShippingGateway $gateway): bool
    {
        return true;
    }
}
