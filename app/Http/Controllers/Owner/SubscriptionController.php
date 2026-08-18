<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Organization;
use App\Models\Subscriptionplans;
use App\Models\TopupPlan;
use App\Models\Notifications;
use App\Models\Setting;
use App\Notifications\CommonMailNotification;
use App\Services\OrderService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

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
        return view('owner.subscription.orders');
    }

    public function ordersData(Request $request)
    {
        $orders = $this->organization($request)->orders()
            ->with(['subscriptionPlan:id,name', 'topupPlan:id,name'])
            ->latest();

        return DataTables::eloquent($orders)
            ->addColumn('item', fn (Order $order) => $order->subscriptionPlan?->name ?? $order->topupPlan?->name ?? '--')
            ->editColumn('order_type', fn (Order $order) => ucfirst($order->order_type))
            ->editColumn('amount', fn (Order $order) => '&#8377;'.number_format((float) $order->amount, 2))
            ->editColumn('status', fn (Order $order) => '<span class="badge bg-secondary">'.e(ucfirst($order->status)).'</span>')
            ->editColumn('payment_status', fn (Order $order) => '<span class="badge '.($order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark').'">'.e(ucfirst($order->payment_status)).'</span>')
            ->editColumn('created_at', fn (Order $order) => $order->created_at->format('d M Y, h:i A'))
            ->orderColumn('item', fn ($query, $direction) => $query->orderByRaw("COALESCE((SELECT name FROM subscription_plans WHERE subscription_plans.id = orders.subscription_plan_id), (SELECT name FROM topup_plans WHERE topup_plans.id = orders.topup_plan_id)) {$direction}"))
            ->rawColumns(['amount', 'status', 'payment_status'])
            ->toJson();
    }

    public function createRenewal(Request $request)
    {
        return view('owner.subscription.renew', ['plans' => Subscriptionplans::active()->get()]);
    }

    public function renew(Request $request, OrderService $orders)
    {
        $request->validate([
            'subscription_plan_id' => 'required|integer',
            'billing_cycle' => ['required', Rule::in(Subscriptionplans::BILLING_CYCLES)],
        ]);
        $organization = $this->organization($request);
        $order = $orders->createSubscriptionOrder($organization, $request->integer('subscription_plan_id'), 'renewal', $request->string('billing_cycle')->toString());
        $this->notifyAdminOfOrder($organization, $order, 'renewal');
        return redirect()->route('owner.orders')->with('success', "Renewal order {$order->order_number} is pending offline-payment approval.");
    }

    public function createTopup()
    {
        return view('owner.subscription.topup', ['topups' => TopupPlan::active()->get()]);
    }

    public function topup(Request $request, OrderService $orders)
    {
        $request->validate(['topup_plan_id' => 'required|integer']);
        $organization = $this->organization($request);
        $order = $orders->createTopupOrder($organization, $request->integer('topup_plan_id'));
        $this->notifyAdminOfOrder($organization, $order, 'topup');
        return redirect()->route('owner.orders')->with('success', "Top-up order {$order->order_number} is pending offline-payment approval.");
    }

    private function notifyAdminOfOrder(Organization $organization, Order $order, string $action): void
    {
        $order->loadMissing(['subscriptionPlan', 'topupPlan']);
        $isRenewal = $action === 'renewal';
        $actionLabel = $isRenewal ? 'Plan Renewal' : 'Top-up Purchase';
        $itemName = $isRenewal ? $order->subscriptionPlan?->name : $order->topupPlan?->name;
        $owner = $organization->users()->oldest()->first();

        try {
            Notifications::create([
                'photo_random_id' => $order->order_number,
                'name' => $organization->organization_name,
                'email' => $owner?->email ?? '',
                'type' => $actionLabel,
                // A null organization makes this an admin-only notification.
                'organization_id' => null,
                'data' => json_encode([
                    'message' => "{$actionLabel} requested by {$organization->organization_name}.",
                    'order_number' => $order->order_number,
                    'item' => $itemName,
                    'amount' => $order->amount,
                    'billing_cycle' => $order->billing_cycle,
                ]),
            ]);

            $adminEmails = Setting::value('admin_email') ?: config('mail.from.address');
            $emails = array_filter(array_map('trim', explode(',', (string) $adminEmails)));

            if ($emails) {
                $details = '<p>Dear Admin,</p>'
                    . "<p>An owner has submitted a {$actionLabel} order.</p>"
                    . '<p><strong>Company:</strong> '.e($organization->organization_name).'</p>'
                    . '<p><strong>Owner email:</strong> '.e($owner?->email ?? '--').'</p>'
                    . '<p><strong>Order number:</strong> '.e($order->order_number).'</p>'
                    . '<p><strong>Item:</strong> '.e($itemName ?? '--').'</p>'
                    . '<p><strong>Amount:</strong> &#8377;'.number_format((float) $order->amount, 2).'</p>'
                    . '<p><strong>Status:</strong> Pending offline-payment approval</p>';
                Notification::route('mail', $emails)->notify(new CommonMailNotification(
                    "Owner {$actionLabel} - {$organization->organization_name}",
                    $details
                ));
            }
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
