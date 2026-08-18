<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use App\Notifications\CommonMailNotification;
use Illuminate\Support\Facades\Notification;
use App\Helpers\ActivityLogger;
use App\Models\Setting;
use App\Models\Notifications;
use Illuminate\Support\Facades\Http;
use App\Models\Subscriptionplans;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Validation\Rule;
class OrganizationsController extends Controller
{
    public function index(Request $request)
    {
        $plans = Subscriptionplans::active()->orderBy('monthly_price')->get();
        $selectedPlan = $request->filled('plan')
            ? $plans->firstWhere('id', $request->integer('plan'))
            : $plans->first();
        $billingCycle = $request->input('billing_cycle', 'monthly');
        abort_unless(in_array($billingCycle, Subscriptionplans::BILLING_CYCLES, true), 404);
        return view('organizations.index', compact('plans', 'selectedPlan', 'billingCycle'));
    }

    public function thankYou()
    {
        return view('organizations.thank-you');
    }


        public function store(Request $request, OrderService $orders, PaymentService $payments)
    {
        $request->validate([
            'organization_name'   => 'required|string|max:255',
            'organization_email'  => 'required|email|unique:users,email',
            // 'mobile_number' => 'numeric|digits_between:10,14',
            'password' => [
                    'required',
                    'min:6',
                    'confirmed',
                    'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z0-9])\S+$/',
                ],
             'g-recaptcha-response' => 'required',
             'terms'              => 'required|accepted',
             'subscription_plan'  => 'required|integer|exists:subscription_plans,id',
             'billing_cycle'       => ['required', Rule::in(Subscriptionplans::BILLING_CYCLES)],
        ],
            [
                'g-recaptcha-response.required' => 'Google captcha field is required.',
                'terms.required' => 'Terms & Conditions is required.',
                'terms.accepted' => 'You must accept the Terms & Conditions.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 6 characters.',
                'password.confirmed' => 'Password and Confirm Password must be the same.',
               'password.regex' => 'Password must contain at least one letter, one number, and one special character.',

            ]);
             $response = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret' => env('RECAPTCHA_SECRET_KEY'),
                    'response' => $request->input('g-recaptcha-response'),
                ]
            );

            if (!$response->json()['success']) {
                return back()->with('error', 'Captcha verification failed.');
            }

        DB::beginTransaction();
        try {
            $plan = Subscriptionplans::active()->findOrFail($request->integer('subscription_plan'));
            $organization = Organization::create([
                'organization_name' => $request->organization_name,
                'business_type'     => $request->business_type,
                'organization_code' => '',
                'subscription_plan' => $plan->id,
                'message'           => $request->message,
                'created_by'        => null,
            ]);

            $organization->organization_code = 'ORG_' . $organization->id;
            $organization->save();

            // 2. Create User (email, password, org_id here)
            $user = User::create([
                'name'            => $request->owner_name, // ya jo bhi field User table mein user ka naam ke liye hai
                'email'           => $request->organization_email,
                'password'        => Hash::make($request->password),
                'phone_number'   => $request->mobile_number,
                'organization_id' => $organization->id,
            ]);
            
            $user->assignRole(['owner', 'employee']);
            $order = $orders->createSubscriptionOrder($organization, $plan->id, 'subscription', $request->input('billing_cycle'));
            if ((float) $order->amount === 0.0) {
                $payments->approveOffline($order, ['notes' => 'Automatically approved free organization plan.']);
            }

            Notifications::create([
                'photo_random_id' => $organization->organization_code,
                'name' => $organization->organization_name,
                'email' => $user->email,
                'type' => 'Corporate Account',
                'organization_id' => $organization->id,
                'data' => json_encode([
                    'message' => 'A corporate account was created from the website.',
                    'plan' => $plan->name,
                    'billing_cycle' => $request->input('billing_cycle'),
                    'order_number' => $order->order_number,
                ]),
            ]);
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }

       $slot = '
            <p>Dear <strong>' . ($user->name ?? 'User') . '</strong>,</p>
            <p>Welcome! Your account has been created successfully.</p>
            <h3>Login Details</h3>';
        $adminUrl = url('/admin/login');
        if (!empty($user->email)) {
            $slot .= '<p> <strong>Login URL:</strong> ' . e($adminUrl) . ' </p> 
            <p><strong>Username / Email:</strong> ' . $user->email . '</p>';
        }

        if (!empty($request->password)) {
            $slot .= '
            <p><strong>Password:</strong> ' . $request->password . '</p>';
        }

        if (!empty($organization->organization_name)) {
            $slot .= '
            <p><strong>Company Name:</strong> ' . $organization->organization_name . '</p>';
        }

         if (!empty($user->phone_number)) {
            $slot .= '
            <p><strong>Phone Number:</strong> ' . $user->phone_number . '</p>';
        }

        if (!empty($plan->name)) {
            $slot .= '
            <p><strong>Subscription Plan:</strong> ' . $plan->name . '</p>';
        }

            $slot .= '
                <hr>
                <h3>Next Steps</h3>
                <ul>
                    <li>Log in using the credentials above.</li>
                    <li>Invite your employees from the dashboard.</li>
                </ul>
                 <hr>

                <h3>Download the Mobile App</h3>

                <p>If you haven\'t already, download the Photo Proof mobile app:</p>
                <p><a href="'.config('app.app_urls.android', '#').'">Download for Android</a> | <a href="'.config('app.app_urls.ios', '#').'">Download for iOS</a></p>

                <hr>';

            $adminSlot = ' <p>Dear Admin,</p> 
            <p>A new company has been registered on the platform.</p>  <hr> <h3>Company Details</h3> '; 
            if (!empty($organization->organization_name)) { 
                $adminSlot .= ' <p><strong>Company Name:</strong> ' . $organization->organization_name . '</p>';
             } 

             if (!empty($user->name)) { 
                $adminSlot .= ' <p><strong>Contact Person Name:</strong> ' . $user->name . '</p>';
             } 
            if (!empty($user->phone_number)) {
                $adminSlot .= '
                <p><strong>Phone Number:</strong> ' . $user->phone_number . '</p>';
            }
            if (!empty($user->email)) { 
                $adminSlot .= ' <p><strong>Contact Person Email:</strong> ' . $user->email . '</p>'; 
            } 
            if (!empty($request->password)) { 
                $adminSlot .= ' <p><strong>Password:</strong> ' . $request->password . '</p>'; 
            } 
            if (!empty($plan->name)) {
                 $adminSlot .= ' <p><strong>Subscription Plan:</strong> ' . $plan->name . '</p>';
             } 
             if (!empty($request->message)) {
                 $adminSlot .= ' <p><strong>Message:</strong> ' . $request->message . '</p>'; 
             } 


             $adminSlot .= ' <hr> <h3>Cookie Information</h3> '; 
             if (!empty($request->initial_landing_page)) {
                    $adminSlot .= '
                        <p><strong>Initial Landing Page:</strong> ' .
                        $request->initial_landing_page .
                        '</p>';
                }
                        
           if (!empty($request->input('submitted_from'))) {
                $adminSlot .= '
                    <p><strong>Submitted From:</strong> ' .
                    $request->input('submitted_from') .
                    '</p>';
            }
            $adminSlot .= ' <p><strong>Date and Time:</strong> ' . now()->format('d M Y, h:i A') . '</p> '; 
            if (!empty($request->ip())) { 
                $adminSlot .= ' <p><strong>IP Address:</strong> ' . $request->ip() . '</p>'; } if (!empty($request->userAgent())) { $adminSlot .= ' <p><strong>Browser:</strong> ' . $request->userAgent() . '</p>'; }

                $adminSlot .= ' <p><strong>Created At:</strong> ' . now()->format('d M Y, h:i A') . '</p> <hr>';

            // ✅ User ko uska content
            try {
                Notification::route('mail', $user->email)
                    ->notify(new CommonMailNotification(
                        'Welcome to Our Portal - Account Created Successfully',
                        $slot
                    ));
            } catch (\Exception $e) {
                report($e);
            }

            // ✅ Admin ko uska content
            try {
                $settings = Setting::first();
                $adminEmails = $settings->admin_email ?? env('ADMIN_EMAIL');
                $emails = array_map('trim', explode(',', $adminEmails));

                Notification::route('mail', $emails)
                    ->notify(new CommonMailNotification(
                        'New Organization Registered - ' . $organization->organization_name,
                        $adminSlot
                    ));
            } catch (\Exception $e) {
                report($e);
            }

        ActivityLogger::log(
            'Create',
            'Organizations',
            'Created new Corporate Account: ' . $request->organization_name
        );

        return redirect()->route('organization.thank-you')->with('success', (float) $order->amount === 0.0
            ? 'Your free plan is active.'
            : "Your company was created. Order {$order->order_number} is awaiting offline-payment approval.");
    }

}
