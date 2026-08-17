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

class BillingController extends Controller
{
    public function plans() { return view('admin.billing.plans', ['organizationPlans' => Subscriptionplans::withCount(['orders as purchasers_count' => fn ($query) => $query->where('payment_status', 'paid')])->latest()->get(), 'topupPlans' => TopupPlan::latest()->get()]); }
    public function storePlan(Request $request)
    {
        if ($request->input('type') === 'topup') {
            $data = $request->validate(['name'=>'required|string|max:255','photo_quantity'=>'required|integer|min:1','price'=>'required|numeric|min:0','state'=>'nullable|boolean']);
            $data['code'] = $this->planCode($data['name']);
            $data['state'] = $request->boolean('state'); TopupPlan::create($data);
            return back()->with('success', 'Top-up plan created.');
        }
        $data = $request->validate(['name'=>'required|string|max:255','description'=>'nullable|string|max:5000','monthly_photo_limit'=>'required|integer|min:1','price'=>'required|numeric|min:0','yearly_price'=>'required|numeric|min:0','billing_cycle'=>['required', Rule::in(Subscriptionplans::BILLING_CYCLES)],'state'=>'nullable|boolean']);
        $data['code'] = $this->planCode($data['name']);
        $data['state'] = $request->boolean('state'); Subscriptionplans::create($data);
        return back()->with('success', 'Plan created.');
    }
    public function updatePlan(Request $request, Subscriptionplans $plan)
    {
        $data = $request->validate(['name'=>'required|string|max:255','description'=>'nullable|string|max:5000','monthly_photo_limit'=>'required|integer|min:1','price'=>'required|numeric|min:0','yearly_price'=>'required|numeric|min:0','billing_cycle'=>['required', Rule::in(Subscriptionplans::BILLING_CYCLES)],'state'=>'required|in:0,1']);
        $plan->update($data);
        return back()->with('success', 'Plan updated.');
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
        $search = trim((string) $request->input('search'));
        $orders = Order::with(['organization.users:id,organization_id,email','subscriptionPlan','topupPlan','payment'])
            ->when($request->filled('plan'), fn ($query) => $query->where('subscription_plan_id', $request->integer('plan')))
            ->when($search !== '', function ($query) use ($search) {
                $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('organization', function ($organization) use ($search) {
                        $organization->where('organization_name', 'like', "%{$search}%")
                            ->orWhereHas('users', fn ($user) => $user->where('email', 'like', "%{$search}%"));
                    });
            })
            ->latest()->paginate(30)->withQueryString();
        return view('admin.billing.orders', compact('orders', 'search'));
    }
    public function subscriptions(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $subscriptions = OrganizationSubscriptions::with(['organization.users:id,organization_id,email','plan'])
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('organization', function ($organization) use ($search) {
                    $organization->where('organization_name', 'like', "%{$search}%")
                        ->orWhereHas('users', fn ($user) => $user->where('email', 'like', "%{$search}%"));
                });
            })
            ->latest()->paginate(30)->withQueryString();
        return view('admin.billing.subscriptions', compact('subscriptions', 'search'));
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
}
