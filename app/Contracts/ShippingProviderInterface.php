<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\ShippingGateway;

interface ShippingProviderInterface
{
    /**
     * Create a new shipment for an order.
     */
    public function createShipment(Order $order, ShippingGateway $gateway, array $options = []): array;

    /**
     * Track a shipment by tracking number.
     */
    public function trackShipment(string $trackingNumber, ShippingGateway $gateway): array;

    /**
     * Cancel a shipment.
     */
    public function cancelShipment(string $trackingNumber, ShippingGateway $gateway): bool;
}
