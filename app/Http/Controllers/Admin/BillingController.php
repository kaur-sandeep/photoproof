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

class BillingController extends Controller
{
    public function plans() { return view('admin.billing.plans', ['organizationPlans' => Subscriptionplans::latest()->get(), 'individualPlans' => Plan::latest()->get(), 'topupPlans' => TopupPlan::latest()->get()]); }
    public function storePlan(Request $request)
    {
        if ($request->input('type') === 'individual') {
            $data = $request->validate(['name'=>'required|string|max:255','photo_limit'=>'required|integer|min:1','price'=>'required|numeric|min:0','state'=>'nullable|boolean']);
            $data['state'] = $request->boolean('state'); Plan::create($data);
            return back()->with('success', 'Individual plan created.');
        }
        if ($request->input('type') === 'topup') {
            $data = $request->validate(['name'=>'required|string|max:255','code'=>'required|string|max:50|unique:topup_plans,code','photo_quantity'=>'required|integer|min:1','price'=>'required|numeric|min:0','state'=>'nullable|boolean']);
            $data['state'] = $request->boolean('state'); TopupPlan::create($data);
            return back()->with('success', 'Top-up plan created.');
        }
        $data = $request->validate(['name'=>'required|string|max:255','code'=>'required|string|max:50|unique:subscription_plans,code','description'=>'nullable|string|max:5000','monthly_photo_limit'=>'required|integer|min:1','price'=>'required|numeric|min:0','duration_days'=>'required|integer|min:1','state'=>'nullable|boolean']);
        $data['state'] = $request->boolean('state'); Subscriptionplans::create($data);
        return back()->with('success', 'Plan created.');
    }
    public function updatePlan(Request $request, Subscriptionplans $plan)
    {
        $data = $request->validate(['name'=>'required|string|max:255','code'=>['required','string','max:50',Rule::unique('subscription_plans','code')->ignore($plan)],'description'=>'nullable|string|max:5000','monthly_photo_limit'=>'required|integer|min:1','price'=>'required|numeric|min:0','duration_days'=>'required|integer|min:1','state'=>'required|in:0,1']);
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
        $data = $request->validate(['name'=>'required|string|max:255','code'=>['required','string','max:50',Rule::unique('topup_plans','code')->ignore($topup)],'photo_quantity'=>'required|integer|min:1','price'=>'required|numeric|min:0','state'=>'required|in:0,1']);
        $topup->update($data);
        return back()->with('success', 'Top-up plan updated.');
    }
    public function setTopupState(TopupPlan $topup, int $state)
    {
        abort_unless(in_array($state, [0, 1], true), 404);
        $topup->update(['state' => $state]);
        return back()->with('success', $state ? 'Top-up plan activated successfully.' : 'Top-up plan inactivated successfully.');
    }
    public function orders() { return view('admin.billing.orders', ['orders' => Order::with(['organization','subscriptionPlan','topupPlan','payment'])->latest()->paginate(30)]); }
    public function subscriptions() { return view('admin.billing.subscriptions', ['subscriptions' => OrganizationSubscriptions::with(['organization','plan'])->latest()->paginate(30)]); }
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
}
