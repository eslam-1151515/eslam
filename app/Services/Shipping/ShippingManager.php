<?php

namespace App\Services\Shipping;

use App\Contracts\ShippingProviderInterface;
use App\Services\Shipping\Drivers\BostaShippingDriver;
use App\Services\Shipping\Drivers\JntShippingDriver;
use App\Services\Shipping\Drivers\EgyptPostShippingDriver;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShippingGateway;
use InvalidArgumentException;

class ShippingManager
{
    /**
     * Get instance of driver by provider name.
     */
    public function driver(string $provider): ShippingProviderInterface
    {
        return match ($provider) {
            'bosta' => new BostaShippingDriver(),
            'jnt' => new JntShippingDriver(),
            'egypt_post' => new EgyptPostShippingDriver(),
            default => throw new InvalidArgumentException("Unsupported shipping provider: {$provider}"),
        };
    }

    /**
     * Create shipment for order using specified provider gateway.
     */
    public function createShipment(Order $order, string $provider): Shipment
    {
        $tenantId = $order->tenant_id;
        $gateway = ShippingGateway::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('provider', $provider)
            ->where('is_active', true)
            ->first();

        if (!$gateway) {
            // Create fallback/mock gateway if not configured
            $gateway = new ShippingGateway([
                'tenant_id' => $tenantId,
                'provider' => $provider,
                'is_active' => true,
                'credentials' => ['api_key' => 'test_mode'],
            ]);
        }

        $driver = $this->driver($provider);
        $result = $driver->createShipment($order, $gateway);

        if (!$result['success']) {
            throw new \RuntimeException($result['error'] ?? 'Shipping creation failed.');
        }

        return Shipment::create([
            'tenant_id' => $tenantId,
            'order_id' => $order->id,
            'provider' => $provider,
            'tracking_number' => $result['tracking_number'],
            'airway_bill_url' => $result['airway_bill_url'],
            'status' => $result['status'] ?? 'created',
            'cost' => $result['cost'] ?? 0.00,
            'raw_response' => $result['raw_response'] ?? [],
        ]);
    }
}
