<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use App\Notifications\CommonMailNotification;
use Illuminate\Support\Facades\Notification;
use App\Helpers\ActivityLogger;
use App\Helpers\DateTime;
use App\Models\Setting;
use App\Models\Notifications;
use Illuminate\Support\Facades\Http;
use App\Models\Subscriptionplans;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
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

     public function logistics(Request $request)
    {
        $plans = Subscriptionplans::active()->orderBy('monthly_price')->get();
        $selectedPlan = $request->filled('plan')
            ? $plans->firstWhere('id', $request->integer('plan'))
            : $plans->first();
        $billingCycle = $request->input('billing_cycle', 'monthly');
        abort_unless(in_array($billingCycle, Subscriptionplans::BILLING_CYCLES, true), 404);
        return view('organizations.logistics', compact('plans', 'selectedPlan', 'billingCycle'));
    }

    public function thankYou()
    {
        return view('organizations.thank-you');
    }


        public function store(Request $request, OrderService $orders, PaymentService $payments)
    {
        $request->validate([
            'organization_name'   => 'required|string|max:255',
            //'organization_email'  => 'required|email|unique:users,email',
            'organization_email' => [
                        'required',
                        'email',
                        'unique:users,email',
                        'unique:admins,email',
                    ],
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
                    'secret' => config('services.recaptcha.secret_key'),
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
             \Log::error('Corporate Account Creation Failed', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }

       $slot = '
            <p>Dear <strong>' . ($user->name ?? 'User') . '</strong>,</p>
            <p>Thank you for creating your logistics account on the PhotoProof platform.</p>
            <p>Your account is currently under review. One of our executives will review your account and activate it.</p>
            <p>Once your account has been activated, you will be notified via email.</p>
            <strong>Please find the below information submitted</strong>';
        if (!empty($user->email)) {
            $slot .= 
            '<p>
                <strong>Username / Email:</strong> ' . e($user->email) . '
            </p>';
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
            $adminSlot .= ' <p><strong>Date and Time:</strong> ' . DateTime::dateFormat(now(), 'd M Y, h:i A') . '</p> '; 
            if (!empty($request->ip())) { 
                $adminSlot .= ' <p><strong>IP Address:</strong> ' . $request->ip() . '</p>'; } if (!empty($request->userAgent())) { $adminSlot .= ' <p><strong>Browser:</strong> ' . $request->userAgent() . '</p>'; }

                $adminSlot .= ' <p><strong>Created At:</strong> ' . DateTime::dateFormat(now(), 'd M Y, h:i A') . '</p> <hr>';

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

public function storeV3(Request $request, OrderService $orders, PaymentService $payments)
{
    $request->validate([
        'organization_name' => 'required|string|max:255',

        'organization_email' => [
            'required',
            'email',
            'unique:users,email',
            'unique:admins,email',
        ],

        'password' => [
            'required',
            'min:6',
            'confirmed',
            'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z0-9])\S+$/',
        ],

        // reCAPTCHA v3 token
        'recaptcha_token' => 'required',

        'terms' => 'required|accepted',

        'subscription_plan' => 'required|integer|exists:subscription_plans,id',

        'billing_cycle' => [
            'required',
            Rule::in(Subscriptionplans::BILLING_CYCLES),
        ],
    ], [
        'recaptcha_token.required' => 'Google captcha verification is required.',

        'terms.required' => 'Terms & Conditions is required.',
        'terms.accepted' => 'You must accept the Terms & Conditions.',

        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 6 characters.',
        'password.confirmed' => 'Password and Confirm Password must be the same.',
        'password.regex' => 'Password must contain at least one letter, one number, and one special character.',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA v3 verification
    |--------------------------------------------------------------------------
    */

    $captchaResponse = Http::asForm()->post(
        'https://www.google.com/recaptcha/api/siteverify',
        [
            'secret' => config('services.recaptcha_v3.secret_key'),
            'response' => $request->input('recaptcha_token'),
            'remoteip' => $request->ip(),
        ]
    );

    $captcha = $captchaResponse->json();


    /*
    |--------------------------------------------------------------------------
    | Verify reCAPTCHA success
    |--------------------------------------------------------------------------
    */

    if (!($captcha['success'] ?? false)) {

        Log::warning('reCAPTCHA v3 verification failed', [
            'captcha_response' => $captcha,
            'ip' => $request->ip(),
        ]);

        return back()
            ->withInput()
            ->with('error', 'Captcha verification failed.');
    }


    /*
    |--------------------------------------------------------------------------
    | Verify reCAPTCHA ACTION
    |--------------------------------------------------------------------------
    */

    if (($captcha['action'] ?? '') !== 'organization_registration') {

        Log::warning('reCAPTCHA v3 invalid action', [
            'action' => $captcha['action'] ?? null,
            'ip' => $request->ip(),
        ]);

        return back()
            ->withInput()
            ->with('error', 'Captcha verification failed.');
    }


    /*
    |--------------------------------------------------------------------------
    | Verify reCAPTCHA SCORE
    |--------------------------------------------------------------------------
    */

    $score = (float) ($captcha['score'] ?? 0);

    // Recommended starting threshold
    if ($score < 0.5) {

        Log::warning('reCAPTCHA v3 low score', [
            'score' => $score,
            'action' => $captcha['action'] ?? null,
            'ip' => $request->ip(),
        ]);

        return back()
            ->withInput()
            ->with('error', 'Captcha verification failed.');
    }


    /*
    |--------------------------------------------------------------------------
    | Create Organization
    |--------------------------------------------------------------------------
    */

    DB::beginTransaction();

    try {

        $plan = Subscriptionplans::active()
            ->findOrFail($request->integer('subscription_plan'));


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


        /*
        |--------------------------------------------------------------------------
        | Create Owner User
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name'            => $request->owner_name,
            'email'           => $request->organization_email,
            'password'        => Hash::make($request->password),
            'phone_number'    => $request->mobile_number,
            'organization_id' => $organization->id,
        ]);


        $user->assignRole(['owner', 'employee']);


        /*
        |--------------------------------------------------------------------------
        | Create Subscription Order
        |--------------------------------------------------------------------------
        */

        $order = $orders->createSubscriptionOrder(
            $organization,
            $plan->id,
            'subscription',
            $request->input('billing_cycle')
        );


        /*
        |--------------------------------------------------------------------------
        | Automatically approve free plan
        |--------------------------------------------------------------------------
        */

        if ((float) $order->amount === 0.0) {

            $payments->approveOffline(
                $order,
                [
                    'notes' => 'Automatically approved free organization plan.'
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Notification Record
        |--------------------------------------------------------------------------
        */

        Notifications::create([
            'photo_random_id' => $organization->organization_code,
            'name'            => $organization->organization_name,
            'email'           => $user->email,
            'type'            => 'Corporate Account',
            'organization_id' => $organization->id,

            'data' => json_encode([
                'message'      => 'A corporate account was created from the website.',
                'plan'         => $plan->name,
                'billing_cycle' => $request->input('billing_cycle'),
                'order_number' => $order->order_number,
            ]),
        ]);


        DB::commit();

    } catch (\Exception $e) {

        Log::error('Corporate Account Creation Failed', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        DB::rollBack();

        return redirect()
            ->back()
            ->with('error', 'Something went wrong: ' . $e->getMessage());
    }


    /*
    |--------------------------------------------------------------------------
    | USER REGISTRATION EMAIL
    |--------------------------------------------------------------------------
    */

    $slot = '
        <p>Dear <strong>' . ($user->name ?? 'User') . '</strong>,</p>

        <p>Welcome! Your account has been created successfully.</p>

        <h3>Login Details</h3>
    ';


    $adminUrl = url('/admin/login');


    if (!empty($user->email)) {

        $slot .= '
            <p>
                <strong>Login URL:</strong>
                <a href="' . e($adminUrl) . '" target="_blank">
                    Click here to login to portal
                </a>
            </p>

            <p>
                <strong>Username / Email:</strong>
                ' . e($user->email) . '
            </p>
        ';
    }


    if (!empty($request->password)) {

        $slot .= '
            <p>
                <strong>Password:</strong>
                ' . e($request->password) . '
            </p>
        ';
    }


    if (!empty($organization->organization_name)) {

        $slot .= '
            <p>
                <strong>Company Name:</strong>
                ' . e($organization->organization_name) . '
            </p>
        ';
    }


    if (!empty($user->phone_number)) {

        $slot .= '
            <p>
                <strong>Phone Number:</strong>
                ' . e($user->phone_number) . '
            </p>
        ';
    }


    if (!empty($plan->name)) {

        $slot .= '
            <p>
                <strong>Subscription Plan:</strong>
                ' . e($plan->name) . '
            </p>
        ';
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

        <p>
            If you haven\'t already, download the Photo Proof mobile app:
        </p>

        <p>
            <a href="' . config('app.app_urls.android', '#') . '">
                Download for Android
            </a>
            |
            <a href="' . config('app.app_urls.ios', '#') . '">
                Download for iOS
            </a>
        </p>

        <hr>
    ';


    /*
    |--------------------------------------------------------------------------
    | ADMIN EMAIL
    |--------------------------------------------------------------------------
    */

    $adminSlot = '
        <p>Dear Admin,</p>

        <p>A new company has been registered on the platform.</p>

        <hr>

        <h3>Company Details</h3>
    ';


    if (!empty($organization->organization_name)) {

        $adminSlot .= '
            <p>
                <strong>Company Name:</strong>
                ' . e($organization->organization_name) . '
            </p>
        ';
    }


    if (!empty($user->name)) {

        $adminSlot .= '
            <p>
                <strong>Contact Person Name:</strong>
                ' . e($user->name) . '
            </p>
        ';
    }


    if (!empty($user->phone_number)) {

        $adminSlot .= '
            <p>
                <strong>Phone Number:</strong>
                ' . e($user->phone_number) . '
            </p>
        ';
    }


    if (!empty($user->email)) {

        $adminSlot .= '
            <p>
                <strong>Contact Person Email:</strong>
                ' . e($user->email) . '
            </p>
        ';
    }


    if (!empty($request->password)) {

        $adminSlot .= '
            <p>
                <strong>Password:</strong>
                ' . e($request->password) . '
            </p>
        ';
    }


    if (!empty($plan->name)) {

        $adminSlot .= '
            <p>
                <strong>Subscription Plan:</strong>
                ' . e($plan->name) . '
            </p>
        ';
    }


    if (!empty($request->message)) {

        $adminSlot .= '
            <p>
                <strong>Message:</strong>
                ' . e($request->message) . '
            </p>
        ';
    }


    /*
    |--------------------------------------------------------------------------
    | Cookie / Submission Information
    |--------------------------------------------------------------------------
    */

    $adminSlot .= '
        <hr>

        <h3>Cookie Information</h3>
    ';


    if (!empty($request->initial_landing_page)) {

        $adminSlot .= '
            <p>
                <strong>Initial Landing Page:</strong>
                ' . e($request->initial_landing_page) . '
            </p>
        ';
    }


    if (!empty($request->input('submitted_from'))) {

        $adminSlot .= '
            <p>
                <strong>Submitted From:</strong>
                ' . e($request->input('submitted_from')) . '
            </p>
        ';
    }


    $adminSlot .= '
        <p>
            <strong>Date and Time:</strong>
            ' . DateTime::dateFormat(now(), 'd M Y, h:i A') . '
        </p>
    ';


    if (!empty($request->ip())) {

        $adminSlot .= '
            <p>
                <strong>IP Address:</strong>
                ' . e($request->ip()) . '
            </p>
        ';
    }


    if (!empty($request->userAgent())) {

        $adminSlot .= '
            <p>
                <strong>Browser:</strong>
                ' . e($request->userAgent()) . '
            </p>
        ';
    }


    $adminSlot .= '
        <p>
            <strong>Created At:</strong>
            ' . DateTime::dateFormat(now(), 'd M Y, h:i A') . '
        </p>

        <hr>
    ';


    /*
    |--------------------------------------------------------------------------
    | Send User Registration Email
    |--------------------------------------------------------------------------
    */

    try {

        Notification::route('mail', $user->email)
            ->notify(new CommonMailNotification(
                'Welcome to Our Portal - Account Created Successfully',
                $slot
            ));

    } catch (\Exception $e) {

        report($e);
    }


    /*
    |--------------------------------------------------------------------------
    | Send Admin Notification Email
    |--------------------------------------------------------------------------
    */

    try {

        $settings = Setting::first();

        $adminEmails = $settings->admin_email ?? env('ADMIN_EMAIL');

        $emails = array_map(
            'trim',
            explode(',', $adminEmails)
        );


        Notification::route('mail', $emails)
            ->notify(new CommonMailNotification(
                'New Organization Registered - ' .
                $organization->organization_name,
                $adminSlot
            ));

    } catch (\Exception $e) {

        report($e);
    }


    /*
    |--------------------------------------------------------------------------
    | Activity Log
    |--------------------------------------------------------------------------
    */

    ActivityLogger::log(
        'Create',
        'Organizations',
        'Created new Corporate Account: ' .
        $request->organization_name
    );


    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('organization.thank-you')
        ->with(
            'success',
            (float) $order->amount === 0.0
                ? 'Your free plan is active.'
                : "Your company was created. Order {$order->order_number} is awaiting offline-payment approval."
        );
}

public function create(Request $request, OrderService $orders, PaymentService $payments)
{
    $email = strtolower(preg_replace('/\s+/', '', (string) $request->input('organization_email')));
    // Sirf email required, baaki sab optional
    $request->validate([
        'organization_email' => [
            'required',
            'email',
            'unique:users,email',
            'unique:admins,email',
        ],
    ]);

    $plan  = null;
    $order = null;

    DB::beginTransaction();
    try {
        if ($request->filled('subscription_plan')) {
            $plan = Subscriptionplans::active()->findOrFail($request->integer('subscription_plan'));
        }

        $organization = Organization::create([
            'organization_name' => null,
            'business_type'     => null,
            'organization_code' => '',
            'subscription_plan' => $plan?->id,
            'message'           => null,
            'created_by'        => null,
        ]);

        $organization->organization_code = 'ORG_' . $organization->id;
        $organization->save();

        $user = User::create([
            'name'            => null, // NOT NULL ho to ''
            'email'           => $request->organization_email,
            'password'        => null, // NOT NULL ho to Hash::make(Str::random(32))
            'phone_number'    => null,
            'organization_id' => $organization->id,
        ]);

        $user->assignRole(['owner', 'employee']);

        if ($plan) {
            $order = $orders->createSubscriptionOrder(
                $organization,
                $plan->id,
                'subscription',
                $request->input('billing_cycle')
            );

            if ((float) $order->amount === 0.0) {
                $payments->approveOffline($order, ['notes' => 'Automatically approved free organization plan.']);
            }
        }

        Notifications::create([
            'photo_random_id' => $organization->organization_code,
            'name'            => $organization->organization_name,
            'email'           => $user->email,
            'type'            => 'Corporate Account',
            'organization_id' => $organization->id,
            'data'            => json_encode([
                'message'       => 'A corporate account was created from the website.',
                'plan'          => $plan?->name,
                'billing_cycle' => $request->input('billing_cycle'),
                'order_number'  => $order?->order_number,
            ]),
        ]);

        DB::commit();
    } catch (\Throwable $e) {
        DB::rollBack();
        return redirect()->back()
            ->withInput()
            ->with('error', 'Something went wrong: ' . $e->getMessage());
    }

    // Password reset link
    $resetUrl = null;
    try {
        $token    = Password::broker()->createToken($user);
        $resetUrl = route('password.generate', [
            'token' => $token,
            'email' => $user->email,
        ]);
    } catch (\Throwable $e) {
        report($e);
    }

    // Helper: value empty ho to row skip, warna escaped output
    $row = fn ($label, $value) => filled($value)
        ? '<p><strong>' . $label . ':</strong> ' . e($value) . '</p>'
        : '';

    $slot = '
        <p>Dear <strong>' . e($user->name ?: 'User') . '</strong>,</p>
        <p>Thank you for creating your logistics account on the PhotoProof platform.</p>
        <p>Your account is currently under review. One of our executives will review your account and activate it.</p>
        <p>Once your account has been activated, you will be notified via email.</p>';
      

    $adminSlot = '
        <p>Dear Admin,</p>
        <p>A new company has been registered on the platform.</p>
        <hr><h3>Company Details</h3>'
        . $row('Contact Person Email', $user->email)
        . $row('Subscription Plan', $plan?->name)
        . '<hr><h3>Cookie Information</h3>'
        . $row('Initial Landing Page', $request->input('initial_landing_page'))
        . $row('Submitted From', $request->input('submitted_from'))
        . $row('Date and Time', DateTime::dateFormat(now(), 'd M Y, h:i A'))
        . $row('IP Address', $request->ip())
        . $row('Browser', $request->userAgent())
        . $row('Created At', DateTime::dateFormat(now(), 'd M Y, h:i A'))
        . '<hr>';

    // User ko mail
    try {
        Notification::route('mail', $user->email)
            ->notify(new CommonMailNotification(
                'Welcome to Our Portal - Account Created Successfully',
                $slot
            ));
    } catch (\Throwable $e) {
        report($e);
    }

    // Admin ko mail (reset link yahan nahi bheja)
    try {
        $settings    = Setting::first();
        $adminEmails = $settings->admin_email ?? env('ADMIN_EMAIL');
        $emails      = array_map('trim', explode(',', $adminEmails));

        Notification::route('mail', $emails)
            ->notify(new CommonMailNotification(
                'New Organization Registered - ' . $user->email,
                $adminSlot
            ));
    } catch (\Throwable $e) {
        report($e);
    }

    ActivityLogger::log(
        'Create',
        'Organizations',
        'Created new Corporate Account: ' . $user->email
    );

    $message = match (true) {
        $order === null                => 'Your account request has been submitted and is under review.',
        (float) $order->amount === 0.0 => 'Your free plan is active.',
        default                        => "Your company was created. Order {$order->order_number} is awaiting offline-payment approval.",
    };

    return redirect()->route('organization.thank-you')->with('success', $message);
}


}
