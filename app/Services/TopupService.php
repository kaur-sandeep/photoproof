<?php

namespace App\Services;

use App\Models\Order;
use RuntimeException;

class TopupService
{
    public function __construct(private SubscriptionService $subscriptions) {}

    public function applyOrder(Order $order): void
    {
        $subscription = $this->subscriptions->activeForOrganization($order->organization_id, true);
        if (!$subscription) {
            throw new RuntimeException('An active organization subscription is required before a top-up can be applied.');
        }
        $subscription->increment('topup_photo_limit', $order->topupPlan->photo_quantity);
    }
}
