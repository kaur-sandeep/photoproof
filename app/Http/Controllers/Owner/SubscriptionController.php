<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Organization;
use App\Models\Subscriptionplans;
use App\Models\TopupPlan;
use App\Services\OrderService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    private function organization(Request $request): Organization
    {
        return Organization::findOrFail($request->user()->organization_id);
    }

    public function index(Request $request, SubscriptionService $subscriptions)
    {
        $organization = $this->organization($request);
        $subscription = $subscriptions->activeForOrganization($organization->id);
        $scheduledRenewal = $subscription
            ? $organization->subscriptions()->with('plan')
                ->where('starts_at', '>', $subscription->expires_at)
                ->orderBy('starts_at')->first()
            : null;
        return view('owner.subscription.index', compact('organization', 'subscription', 'scheduledRenewal'));
    }

    public function orders(Request $request)
    {
        $organization = $this->organization($request);
        $orders = $organization->orders()->with(['subscriptionPlan', 'topupPlan', 'payment'])->latest()->paginate(20);
        return view('owner.subscription.orders', compact('orders'));
    }

    public function createRenewal(Request $request)
    {
        return view('owner.subscription.renew', ['plans' => Subscriptionplans::active()->get()]);
    }

    public function renew(Request $request, OrderService $orders)
    {
        $request->validate(['subscription_plan_id' => 'required|integer']);
        $order = $orders->createSubscriptionOrder($this->organization($request), $request->integer('subscription_plan_id'), 'renewal');
        return redirect()->route('owner.orders')->with('success', "Renewal order {$order->order_number} is pending offline-payment approval.");
    }

    public function createTopup()
    {
        return view('owner.subscription.topup', ['topups' => TopupPlan::active()->get()]);
    }

    public function topup(Request $request, OrderService $orders)
    {
        $request->validate(['topup_plan_id' => 'required|integer']);
        $order = $orders->createTopupOrder($this->organization($request), $request->integer('topup_plan_id'));
        return redirect()->route('owner.orders')->with('success', "Top-up order {$order->order_number} is pending offline-payment approval.");
    }
}
