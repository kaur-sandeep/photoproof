<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrganizationSubscriptions;
use Illuminate\Support\Carbon;

class SubscriptionService
{
    public function activateOrder(Order $order): OrganizationSubscriptions
    {
        $plan = $order->subscriptionPlan;
        $current = OrganizationSubscriptions::where('organization_id', $order->organization_id)
            ->where('state', true)->orderByDesc('expires_at')->lockForUpdate()->first();
        $start = $order->order_type === 'renewal' && $current && $current->expires_at->isFuture()
            ? $current->expires_at->copy()->addSecond() : now();

        return OrganizationSubscriptions::create([
            'organization_id' => $order->organization_id,
            'subscription_plan_id' => $plan->id,
            'starts_at' => $start,
            'expires_at' => $start->copy()->addDays($plan->duration_days),
            'monthly_photo_limit' => $plan->monthly_photo_limit,
            'monthly_photo_used' => 0,
            'topup_photo_limit' => 0,
            'topup_photo_used' => 0,
            'state' => true,
        ]);
    }

    public function activeForOrganization(int $organizationId, bool $lock = false): ?OrganizationSubscriptions
    {
        $query = OrganizationSubscriptions::where('organization_id', $organizationId)->where('state', true)
            ->whereDate('starts_at', '<=', today())->whereDate('expires_at', '>=', today())->orderByDesc('starts_at');
        return $lock ? $query->lockForUpdate()->first() : $query->first();
    }
}
