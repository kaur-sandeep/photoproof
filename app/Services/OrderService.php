<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Organization;
use App\Models\Subscriptionplans;
use App\Models\TopupPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function createSubscriptionOrder(Organization $organization, int $planId, string $type = 'subscription', string $billingCycle = 'monthly'): Order
    {
        $plan = Subscriptionplans::active()->findOrFail($planId);

        if (!in_array($billingCycle, Subscriptionplans::BILLING_CYCLES, true)) {
            throw ValidationException::withMessages(['billing_cycle' => 'Invalid billing cycle.']);
        }
        if ($billingCycle === 'yearly' && $plan->yearly_price === null) {
            throw ValidationException::withMessages(['billing_cycle' => 'Yearly billing is not available for this plan.']);
        }

        return $this->create($organization, $type, $plan, null, $billingCycle);
    }

    public function createTopupOrder(Organization $organization, int $topupPlanId): Order
    {
        $topup = TopupPlan::active()->findOrFail($topupPlanId);

        return $this->create($organization, 'topup', null, $topup);
    }

    private function create(Organization $organization, string $type, ?Subscriptionplans $plan, ?TopupPlan $topup, ?string $billingCycle = null): Order
    {
        return DB::transaction(function () use ($organization, $type, $plan, $topup, $billingCycle) {
            $sequence = str_pad((string) (Order::whereDate('created_at', today())->lockForUpdate()->count() + 1), 5, '0', STR_PAD_LEFT);
            $order = Order::create([
                'order_number' => 'PP-ORD-'.now()->format('Ymd').'-'.$sequence,
                'organization_id' => $organization->id,
                'subscription_plan_id' => $plan?->id,
                'topup_plan_id' => $topup?->id,
                'order_type' => $type,
                'billing_cycle' => $plan ? $billingCycle : null,
                'amount' => $plan ? ($billingCycle === 'yearly' ? $plan->yearly_price : $plan->price) : $topup->price,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);
            $order->payment()->create(['payment_method' => 'offline', 'amount' => $order->amount, 'status' => 'pending']);

            return $order;
        });
    }
}
