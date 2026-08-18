<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrganizationSubscriptions;
use App\Models\Subscriptionplans;
use App\Models\Plan;
use App\Models\TopupPlan;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BillingController extends Controller
{
    public function plans()
    {
        return view('admin.billing.plans');
    }

    public function plansData()
    {
        return DataTables::of(Subscriptionplans::query()->withCount([
            'orders as purchasers_count' => fn ($query) => $query->where('payment_status', 'paid'),
        ]))
            ->editColumn('monthly_price', fn (Subscriptionplans $plan) => '&#8377;'.number_format((float) $plan->monthly_price, 2))
            ->editColumn('yearly_price', fn (Subscriptionplans $plan) => '&#8377;'.number_format((float) $plan->yearly_price, 2))
            ->addColumn('state', fn (Subscriptionplans $plan) => view('admin.billing.partials.plan-state', compact('plan'))->render())
            ->addColumn('actions', fn (Subscriptionplans $plan) => '<a class="btn btn-sm btn-warning" href="'.route('admin.billing.plans.edit', $plan).'">Edit</a>')
            ->rawColumns(['state', 'actions', 'monthly_price', 'yearly_price'])
            ->make(true);
    }

    public function createPlan()
    {
        return view('admin.billing.plan-form', ['plan' => new Subscriptionplans()]);
    }

    public function editPlan(Subscriptionplans $plan)
    {
        return view('admin.billing.plan-form', compact('plan'));
    }

    public function storePlan(Request $request)
    {
        $data = $this->validatePlan($request);
        $data['code'] = $this->planCode($data['name']);
        Subscriptionplans::create($data);
        return redirect()->route('admin.billing.plans')->with('success', 'Plan created.');
    }
    public function updatePlan(Request $request, Subscriptionplans $plan)
    {
        $data = $this->validatePlan($request);
        $plan->update($data);
        return redirect()->route('admin.billing.plans')->with('success', 'Plan updated.');
    }
    public function updateIndividual(Request $request, Plan $plan)
    {
        $data = $request->validate(['name'=>'required|string|max:255','photo_limit'=>'required|integer|min:1','price'=>'required|numeric|min:0','state'=>'required|in:0,1']);
        $plan->update($data);
        return back()->with('success', 'Individual plan updated successfully.');
    }
    public function setIndividualState(Plan $plan, int $state)
    {
        abort_unless(in_array($state, [0, 1], true), 404);
        $plan->update(['state' => $state]);
        return back()->with('success', $state ? 'Individual plan activated successfully.' : 'Individual plan inactivated successfully.');
    }
    public function setOrganizationState(Subscriptionplans $plan, int $state)
    {
        abort_unless(in_array($state, [0, 1], true), 404);
        $plan->update(['state' => $state]);
        return back()->with('success', $state ? 'Corporate plan activated successfully.' : 'Corporate plan inactivated successfully.');
    }
    public function topups() { return view('admin.billing.topups', ['topups' => TopupPlan::latest()->get()]); }
    public function storeTopup(Request $request)
    {
        $data = $request->validate(['name'=>'required|string|max:255','code'=>'required|string|max:50|unique:topup_plans,code','photo_quantity'=>'required|integer|min:1','price'=>'required|numeric|min:0','state'=>'nullable|boolean']);
        $data['state'] = $request->boolean('state'); TopupPlan::create($data);
        return back()->with('success', 'Top-up plan created.');
    }
    public function updateTopup(Request $request, TopupPlan $topup)
    {
        $data = $request->validate(['name'=>'required|string|max:255','photo_quantity'=>'required|integer|min:1','price'=>'required|numeric|min:0','state'=>'required|in:0,1']);
        $topup->update($data);
        return back()->with('success', 'Top-up plan updated.');
    }
    public function setTopupState(TopupPlan $topup, int $state)
    {
        abort_unless(in_array($state, [0, 1], true), 404);
        $topup->update(['state' => $state]);
        return back()->with('success', $state ? 'Top-up plan activated successfully.' : 'Top-up plan inactivated successfully.');
    }
    public function orders(Request $request)
    {
        return view('admin.billing.orders');
    }

    public function ordersData(Request $request)
    {
        $orders = Order::query()->with(['organization.users:id,organization_id,email', 'subscriptionPlan:id,name', 'topupPlan:id,name'])
            ->when($request->filled('plan'), fn ($query) => $query->where('subscription_plan_id', $request->integer('plan')));

        return DataTables::eloquent($orders)
            ->addColumn('organization_name', fn (Order $order) => $order->organization?->organization_name ?? '--')
            ->addColumn('email', fn (Order $order) => $order->organization?->users->sortBy('created_at')->first()?->email ?? '--')
            ->addColumn('item', fn (Order $order) => ucfirst($order->order_type).': '.($order->subscriptionPlan?->name ?? $order->topupPlan?->name ?? '--'))
            ->editColumn('amount', fn (Order $order) => '&#8377;'.number_format((float) $order->amount, 2))
            ->editColumn('created_at', fn (Order $order) => $order->created_at->format('d M Y, h:i A'))
            ->editColumn('status', fn (Order $order) => '<span class="badge bg-secondary">'.e(ucfirst($order->status)).'</span>')
            ->editColumn('payment_status', fn (Order $order) => '<span class="badge '.($order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark').'">'.e(ucfirst($order->payment_status)).'</span>')
            ->addColumn('actions', function (Order $order) {
                if ($order->payment_status !== 'pending' || ! in_array($order->status, ['pending', 'processing'], true)) return '';
                return '<form method="post" action="'.route('admin.billing.orders.approve', $order).'">'.csrf_field().'<button class="btn btn-sm btn-success">Approve</button></form>';
            })
            ->filterColumn('organization_name', fn ($query, $keyword) => $query->whereHas('organization', fn ($organization) => $organization->where('organization_name', 'like', "%{$keyword}%")))
            ->filterColumn('email', fn ($query, $keyword) => $query->whereHas('organization.users', fn ($user) => $user->where('email', 'like', "%{$keyword}%")))
            ->filterColumn('item', fn ($query, $keyword) => $query->where(fn ($plans) => $plans->whereHas('subscriptionPlan', fn ($plan) => $plan->where('name', 'like', "%{$keyword}%"))->orWhereHas('topupPlan', fn ($plan) => $plan->where('name', 'like', "%{$keyword}%"))))
            ->rawColumns(['amount', 'status', 'payment_status', 'actions'])
            ->toJson();
    }
    public function subscriptions(Request $request)
    {
        return view('admin.billing.subscriptions');
    }

    public function subscriptionsData()
    {
        return DataTables::eloquent(OrganizationSubscriptions::query()->with(['organization.users:id,organization_id,email', 'plan:id,name']))
            ->addColumn('organization_name', fn (OrganizationSubscriptions $subscription) => $subscription->organization?->organization_name ?? '--')
            ->addColumn('email', fn (OrganizationSubscriptions $subscription) => $subscription->organization?->users->sortBy('created_at')->first()?->email ?? '--')
            ->addColumn('plan_name', fn (OrganizationSubscriptions $subscription) => $subscription->plan?->name ?? '--')
            ->editColumn('starts_at', fn (OrganizationSubscriptions $subscription) => $subscription->starts_at->format('d M Y'))
            ->editColumn('expires_at', fn (OrganizationSubscriptions $subscription) => $subscription->expires_at->format('d M Y'))
            ->addColumn('monthly_usage', fn (OrganizationSubscriptions $subscription) => $subscription->monthly_photo_used.'/'.$subscription->monthly_photo_limit)
            ->addColumn('topup_usage', fn (OrganizationSubscriptions $subscription) => $subscription->topup_photo_used.'/'.$subscription->topup_photo_limit)
            ->addColumn('subscription_status', function (OrganizationSubscriptions $subscription) {
                if ($subscription->starts_at->isFuture()) return '<span class="badge bg-info">Renewal scheduled</span>';
                if ($subscription->expires_at->isPast()) return '<span class="badge bg-secondary">Expired</span>';
                return '<span class="badge bg-success">Active</span>';
            })
            ->filterColumn('organization_name', fn ($query, $keyword) => $query->whereHas('organization', fn ($organization) => $organization->where('organization_name', 'like', "%{$keyword}%")))
            ->filterColumn('email', fn ($query, $keyword) => $query->whereHas('organization.users', fn ($user) => $user->where('email', 'like', "%{$keyword}%")))
            ->filterColumn('plan_name', fn ($query, $keyword) => $query->whereHas('plan', fn ($plan) => $plan->where('name', 'like', "%{$keyword}%")))
            ->rawColumns(['subscription_status'])
            ->toJson();
    }
    public function approve(Request $request, Order $order, PaymentService $payments)
    {
        $data = $request->validate(['transaction_reference'=>'nullable|string|max:255','payment_date'=>'nullable|date','notes'=>'nullable|string|max:2000']);
        $payments->approveOffline($order, $data);
        return back()->with('success', 'Offline payment approved and order completed.');
    }
    public function cancel(Order $order)
    {
        if ($order->payment_status !== 'pending') return back()->with('error', 'Only unpaid orders can be cancelled.');
        $order->update(['status' => 'cancelled']); return back()->with('success', 'Order cancelled.');
    }

    private function planCode(string $name): string
    {
        return Str::upper(Str::substr(Str::slug($name), 0, 40) . '-' . Str::random(6));
    }

    private function validatePlan(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'monthly_photo_limit' => ['required', 'integer', 'min:1'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['required', 'numeric', 'min:0'],
            'state' => ['required', 'in:0,1'],
        ]);
    }
}
