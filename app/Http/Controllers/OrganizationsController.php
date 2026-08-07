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
class OrganizationsController extends Controller
{
    public function index()
    {
        return view('organizations.index');
    }


        public function store(Request $request)
    {
        $request->validate([
            'organization_name'   => 'required|string|max:255',
            'organization_email'  => 'required|email|unique:users,email',
            // 'mobile_number' => 'numeric|digits_between:10,14',
            'password' => 'required|min:6',
        ]);

        DB::beginTransaction();
        try {
            $organization = Organization::create([
                'organization_name' => $request->organization_name,
                'business_type'     => $request->business_type,
                'organization_code' => '',
                'subscription_plan' => '',
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
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage()); 
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }

       $slot = '
            <p>Dear <strong>' . ($user->name ?? 'User') . '</strong>,</p>

            <p>Welcome! Your organization has been created successfully.</p>

            <hr>

            <h3>Login Details</h3>

            <p><strong>Login URL:</strong>
                <a href="'.url('/login').'">'.url('/login').'</a>
            </p>

            <p><strong>Username / Email:</strong> '.$user->email.'</p>

            <p><strong>Temporary Password:</strong> '.$request->password.'</p>

            <p><strong>Organization Name:</strong> '.$organization->organization_name.'</p>
            <hr>

            <h3>Next Steps</h3>

            <ul>
                <li>Log in using the credentials above.</li>
                <li>Change your password after your first login.</li>
                <li>Invite your employees from the dashboard.</li>
                <li>Assign roles and permissions to your employees.</li>
            </ul>

            <p>If you have any questions, please contact our support team.</p>';

            // ✅ Admin ke liye alag content
            $adminSlot = '
            <p>Dear Admin,</p>

            <p>A new organization has been registered on the platform.</p>

            <hr>

            <h3>Organization Details</h3>

            <p><strong>Organization Name:</strong> '.$organization->organization_name.'</p>
            <p><strong>Contact Person Name:</strong> '.$user->name.'</p>
            <p><strong>Contact Person Email:</strong> '.$user->email.'</p>
            <p><strong>Temporary Password:</strong> '.$request->password.'</p>
            <p><strong>Created At:</strong> '.now()->format('d M Y, h:i A').'</p>

            <hr>';

            // ✅ User ko uska content
            try {
                Notification::route('mail', $user->email)
                    ->notify(new CommonMailNotification(
                        'Welcome to Our Portal - Account Created Successfully',
                        $slot
                    ));
            } catch (\Exception $e) {
                dd('User Mail Error: ' . $e->getMessage());
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
                dd('Admin Mail Error: ' . $e->getMessage());
            }

        ActivityLogger::log(
            'Create',
            'Organizations',
            'Created new organization: ' . $request->organization_name
        );

        return redirect()->back()->with('success', 'Your organization registration request has been submitted successfully. It is currently pending approval. Our administrator will contact you shortly.');
    }

}
