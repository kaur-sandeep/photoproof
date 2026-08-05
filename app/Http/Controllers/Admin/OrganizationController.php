<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ActivityLogger;
use App\Models\Notifications;
use App\Notifications\CommonMailNotification;
use Illuminate\Support\Facades\Notification;
use App\Helpers\DateTime;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Subscriptionplans;
use App\Models\OrganizationSubscriptions;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Validation\Rule;
class OrganizationController extends Controller
{
    public function index(){
       

        return view('admin.organization.index');
    }

public function list(Request $request){
    $organizationId = $request->organization_id;
    $organizations = Organization::where('state', '!=', -1)
    ->with('users:id,organization_id,email', 'subscription.plan')
    ->withCount(['users as employee_count', 'photoDetails as photo_count'])
    ->orderBy('created_at', 'desc')
    ->get();

    return DataTables::of($organizations)
        ->addIndexColumn()
        ->addColumn('organization_name', function ($organizations) {
            return $organizations->organization_name ?? '--';
        })
        ->addColumn('organization_email', function ($organizations) {
            return optional($organizations->users->sortBy('created_at')->first())->email ?? '--';
        })
        ->addColumn('organization_code', function ($organizations) {
            return 'org_'.$organizations->id ?? '--';
        })
        ->addColumn('plan', function ($organizations) {
            return $organizations->subscription->plan->name ?? '--';
        })
        ->addColumn('limit', function ($organizations) {
            return $organizations->subscription->monthly_photo_limit ?? '--';
        })
        ->addColumn('employee_count', function ($organizations) {
            $count = $organizations->employee_count ?? 0;
            $url = route('admin.organizations.employees', [
                'organization_id' => $organizations->id,
                'organization_name' => $organizations->organization_name,
            ]);
            return '<span class="badge bg-info" style="font-size: 1.2rem; padding: 0.6em 1em; border-radius: 0.5rem; display: inline-block;">
                <a href="'.$url.'" style="color:#fff;">'.$count.'</a>
            </span>';
        })
         ->addColumn('organization_name', function ($organizations) {
            return $organizations->organization_name ?? '--';
        })
       ->addColumn('message', function ($organizations) {
    return '<div class="message-wrap">'
            . e($organizations->message ?? '--') .
           '</div>';
})
->rawColumns(['message'])
        ->addColumn('photo_count', function ($organizations) {
                $count = $organizations->photo_count ?? 0;
                $url = route('admin.organizations.photos', [
                    'organization_id' => $organizations->id,
                    'organization_name' => $organizations->organization_name,
                ]);
                return '<span class="badge bg-info" style="
                    font-size: 1.2rem; 
                    padding: 0.6em 1em; 
                    text-decoration: none; 
                    border-radius: 0.5rem;
                    display: inline-block;
                "><a href="'.$url.'" style="color:#fff;">
                    '.$count.'
                </a></span>';
            })
        ->addColumn('status', function ($organizations) {
            if ($organizations->state == 1) {
                return '<button class="btn btn-sm btn-success toggle-status" data-id="'.$organizations->id.'" data-status="0">Active</button>';
            }
            if ($organizations->state == 0) {
                return '<button class="btn btn-sm btn-warning toggle-status" data-id="'.$organizations->id.'" data-status="1">Inactive</button>';
            }
            return '<span class="badge bg-danger">Deleted</span>';
        })
        ->addColumn('organization_created', function ($organizations) {
            return DateTime::dateFormat($organizations->created_at) ?? '--';
        })
        ->addColumn('actions', function ($organizations) {
            return '
             <a href="'.route('admin.organization.edit.data', $organizations->id).'" class="btn btn-sm btn-warning">Edit</a>
             <button class="btn btn-sm btn-danger delete-user" data-id="'.$organizations->id.'">Delete</button>';
        })
        ->rawColumns(['status', 'actions', 'employee_count', 'photo_count','message'])
        ->make(true);
}

    // public function employeeList(Request $request, $organizationId){
    //     // dd($organizationId);
    //     $users = User::where('state', '!=', -1)->where('organization_id',$organizationId)->orderBy('created_at', 'desc')->get();
    //     return DataTables::of($users)
    //     ->addIndexColumn()
    //     ->addColumn('name', function ($users) {
    //         return $users->name ?? '--'; // if device is null, show --
    //     })
    //     ->addColumn('email', function ($users) {
    //         return $users->email ?? '--'; // if device is null, show --
    //     })
    //     ->addColumn('phone_number', function ($users) {
    //         return $users->phone_number ?? '--'; // if device is null, show --
    //     })
    //     ->addColumn('status', function ($users) {
    //             if ($users->state == 1) {
    //                 return '<button class="btn btn-sm btn-success toggle-status" data-id="'.$users->id.'" data-status="0">Active</button>';
    //             }
    //             if ($users->state == 0) {
    //                 return '<button class="btn btn-sm btn-warning toggle-status" data-id="'.$users->id.'" data-status="1">Pending</button>';
                    
    //             }
    //             return '<span class="badge bg-danger">Deleted</span>';
    //     })
    //     ->addColumn('actions', function ($users) {
    //         // return '<a href="'.route('admin.users.show.data', $admins->id).'" class="btn btn-sm btn-primary">View</a>
    //         //         <a href="'.route('admin.users.edit.data', $admins->id).'" class="btn btn-sm btn-warning">Edit</a>
    //         //         <button class="btn btn-sm btn-danger delete-user" data-id="'.$admins->id.'">Delete</button>';
    //          return '<a href="'.route('owner.employee.edit.data', $users->id).'" class="btn btn-sm btn-warning">Edit</a>
    //                 <button class="btn btn-sm btn-danger delete-user" data-id="'.$users->id.'">Delete</button>';
            
    //     })
    //     ->rawColumns(['status', 'actions'])
    //     ->make(true);
    // }

    public function create(){
        $allPlans = Subscriptionplans::all();
        return view('admin.organization.add',compact('allPlans'));
    }

    public function addOrganization(Request $request)
    {
        $request->validate([
            'organization_name'   => 'required|string|max:255',
            'organization_email'  => ['required', 'email', Rule::unique('users', 'email')],
            'subscription_plan'   => 'required|exists:subscription_plans,id',
            'password'            => 'required|min:6',
        ], [
            'organization_email.unique' => 'This email is already registered. Please use a different email address.',
        ]);

        DB::beginTransaction();
        try {
            $organization = Organization::create([
                'organization_name'  => $request->organization_name,
                'business_type'      => $request->business_type,
                'organization_code'  => '',
                'subscription_plan'  => $request->subscription_plan,
                'message'            => $request->message,
                'enable_photo_email' => $request->boolean('email_enabled'),
                'created_by'         => Auth::user()->id,
                'state'              => 1
            ]);

            $organization->organization_code = 'ORG_' . $organization->id;
            $organization->save();

            // 2. Create User (email, password, org_id here)
            $user = User::create([
                'name'            => $request->owner_name,
                'email'           => $request->organization_email,
                'password'        => Hash::make($request->password),
                'phone_number'    => $request->mobile_number,
                'organization_id' => $organization->id,
            ]);

            $user->assignRole(['owner', 'employee']);
            $id = $request->subscription_plan;
            $getPlanDataById = Subscriptionplans::find($id);
            $startDate = Carbon::now();
            $expiresDate = $startDate->copy()->addDays($getPlanDataById->duration_days);
            $OrganizationSubscriptions = OrganizationSubscriptions::create([
                'organization_id'      => $organization->id,
                'subscription_plan_id' => $request->subscription_plan,
                'starts_at'             => $startDate,
                'expires_at'            => $expiresDate,
                'monthly_photo_limit'   => $getPlanDataById->monthly_photo_limit,
                'monthly_photo_used'    => 0,
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }

        $slot = '
            <p>Dear <strong>' . ($user->name ?? 'User') . '</strong>,</p>
            <p>Welcome! Your organization has been created successfully.</p>
            <hr>
            <h3>Login Details</h3>
            <p><strong>Login URL:</strong> <a href="'.url('/admin/login').'">'.url('/admin/login').'</a></p>
            <p><strong>Username / Email:</strong> '.$user->email.'</p>
            <p><strong>Temporary Password:</strong> '.$request->password.'</p>
            <p><strong>Organization Name:</strong> '.$organization->organization_name.'</p>
            <p><strong>Organization Code:</strong> '.$organization->organization_code.'</p>
            <p><strong>Subscription Plan:</strong> '.$getPlanDataById->name.'</p>
            <hr>
            <h3>Next Steps</h3>
            <ul>
                <li>Log in using the credentials above.</li>
                <li>Change your password after your first login.</li>
                <li>Invite your employees from the dashboard.</li>
                <li>Assign roles and permissions to your employees.</li>
            </ul>';

        // ✅ Admin ke liye alag content
        $adminSlot = '
            <p>Dear Admin,</p>
            <p>A new organization has been registered on the platform.</p>
            <hr>
            <h3>Organization Details</h3>
            <p><strong>Organization Name:</strong> '.$organization->organization_name.'</p>
            <p><strong>Organization Code:</strong> '.$organization->organization_code.'</p>
            <p><strong>Owner Name:</strong> '.$user->name.'</p>
            <p><strong>Owner Email:</strong> '.$user->email.'</p>
            <p><strong>Temporary Password:</strong> '.$request->password.'</p>
            <p><strong>Subscription Plan:</strong> '.$getPlanDataById->name.'</p>
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
            // mail fail ho to bhi flow continue rahega
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
            // mail fail ho to bhi flow continue rahega
        }

        ActivityLogger::log(
            'Create',
            'Organizations',
            'Created new organization: ' . $request->organization_name
        );
       return redirect()->back()->with('success', 'Organization added successfully!');
    }

    public function showOrganization(Request $request,$id)
    {
        $organization = Organization::find($id);
        return view('admin.organization.show', compact('organization'));
    }

     public function editOrganization(Request $request,$id)
    {
        $organization = Organization::find($id);
        $user_data = User::where('organization_id',$id)->first();
        $allPlans = Subscriptionplans::all();
        return view('admin.organization.edit', compact('organization','allPlans','user_data'));
    }

    // public function updateOrganization(Request $request, $id)
    // {
    //     $organization = Organization::find($id);
    //     if (!$organization) {
    //         return redirect()->back()->with('error', 'Organization not found.');
    //     }

    //     $request->validate([
    //         'organization_name' => 'required|string|max:255',
    //         // 'business_type' => 'required|string|max:255',
    //         // 'owner_name' => 'required|string|max:255',
    //         'organization_email' => 'required|email|unique:organizations,organization_email,' . $organization->id,
    //         'mobile_number' => 'numeric|digits_between:10,14',
    //         // 'subscription_plan' => 'required|string|max:255'
    //     ]);

    //     $organization->update([
    //         'organization_name' => $request->organization_name,
    //         'business_type' => $request->business_type,
    //         'owner_name' => $request->owner_name,
    //         'organization_email' => $request->organization_email,
    //         'mobile_number' => $request->mobile_number,
    //         'subscription_plan' => $request->subscription_plan
    //     ]);

    //     ActivityLogger::log(
    //         'Update',
    //         'Organizations',
    //         'Updated organization: ' . $request->organization_name
    //     );

    //     return redirect()->back()->with('success', 'Organization updated successfully!');
    // }

// public function updateOrganization(Request $request, $id)
// {

//     $organization = Organization::find($id);

//     if (!$organization) {
//         return redirect()->back()->with('error', 'Organization not found.');
//     }
//     $request->validate([
//         'organization_name'  => 'required|string|max:255',
//         'mobile_number'      => 'nullable|numeric|digits_between:10,14',
//         'password'           => 'nullable|min:6',
//     ]);

//     DB::beginTransaction();
// // dd((int)$request->subscription_plan);
//     try {

//         // Update Organization
//         $organization->update([
//             'business_type'     => $request->business_type,
//             'subscription_plan' =>  (int)$request->subscription_plan,
//             'message'           => $request->message,
//         ]);

//         // Update Owner User
//         $user = User::where('organization_id', $organization->id)->first();

//         if ($user) {

//             $userData = [
//                 'name'          => $request->owner_name,
//                 'email'         => $request->organization_email,
//                 'phone_number'  => $request->mobile_number,
//             ];


//             if ($request->filled('password')) {
//                 $userData['password'] = Hash::make($request->password);
//             }


//             $user->update($userData);
         
//         }


//         // Subscription Plan
//         $plan = Subscriptionplans::find($request->subscription_plan);

//         if ($plan) {

//             $startDate = Carbon::now();

//             $expiresDate = $startDate->copy()
//                 ->addDays($plan->duration_days);
//             $organizationSubscription = OrganizationSubscriptions::where(
//                 'organization_id',
//                 $organization->id
//             )->first();


//             if ($organizationSubscription) {

//                 $organizationSubscription->update([
//                     'subscription_plan_id' => $plan->id,
//                     'starts_at'            => $startDate,
//                     'expires_at'           => $expiresDate,
//                     'monthly_photo_limit'  => $plan->monthly_photo_limit,
//                     'monthly_photo_used'   => 0,
//                     'max_employees'        => $plan->max_employees,
//                 ]);

//             } else {

//                 $newSubscription = OrganizationSubscriptions::create([
//                     'organization_id'       => $organization->id,
//                     'subscription_plan_id'  => $plan->id,
//                     'starts_at'             => $startDate,
//                     'expires_at'            => $expiresDate,
//                     'monthly_photo_limit'  => $plan->monthly_photo_limit,
//                     'monthly_photo_used'   => 0,
//                     'max_employees'        => $plan->max_employees,
//                 ]);
//             }
//         }

//         DB::commit();

//     } catch (\Exception $e) {

//         DB::rollBack();
//     }

//       ActivityLogger::log(
//             'Update',
//             'Organizations',
//             'Updated organization: ' . $request->organization_name
//         );

//         return redirect()->back()->with('success', 'Organization updated successfully!');


    
// }

public function updateOrganization(Request $request, $id)
{
    $traceId = uniqid('org_update_');

    \Log::info("[$traceId] === updateOrganization START ===", [
        'organization_id' => $id,
        'all_request_data' => $request->all(),
    ]);

    $organization = Organization::find($id);

    // if (!$organization) {
    //     \Log::error("[$traceId] Organization not found for id: $id");
    //     return redirect()->back()->with('error', 'Organization not found.');
    // }

    // \Log::info("[$traceId] Organization found before update", [
    //     'current_subscription_plan' => $organization->subscription_plan,
    // ]);

    $request->validate([
        'organization_name'  => 'required|string|max:255',
        'mobile_number'      => 'nullable|numeric|digits_between:10,14',
        'password'           => 'nullable|min:6',
    ]);

    // \Log::info("[$traceId] Validation passed", [
    //     'subscription_plan_raw' => $request->subscription_plan,
    //     'subscription_plan_cast_int' => (int) $request->subscription_plan,
    // ]);

    DB::beginTransaction();

    try {

        $updateData = [
            'organization_name' => $request->organization_name,
            'business_type'     => $request->business_type,
            'subscription_plan' => (int) $request->subscription_plan,
            'enable_photo_email' => $request->boolean('email_enabled'),
            'message'           => $request->message,
        ];

        // \Log::info("[$traceId] About to update Organization with data:", $updateData);

        $updateResult = $organization->update($updateData);

        // \Log::info("[$traceId] Organization update() returned:", [
        //     'result' => $updateResult,
        //     'subscription_plan_after_update_in_memory' => $organization->subscription_plan,
        // ]);

        // Update Owner User
        $user = User::where('organization_id', $organization->id)->first();

        if ($user) {

            // \Log::info("[$traceId] Owner user found", ['user_id' => $user->id]);

            $userData = [
                'name'          => $request->owner_name,
                'email'         => $request->organization_email,
                'phone_number'  => $request->mobile_number,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
                \Log::info("[$traceId] Password will be updated for user_id: {$user->id}");
            }

            $user->update($userData);

            // \Log::info("[$traceId] User updated", $userData);

        } else {
            \Log::warning("[$traceId] No owner user found for organization_id: {$organization->id}");
        }

        // Subscription Plan
        $plan = Subscriptionplans::find($request->subscription_plan);

        // \Log::info("[$traceId] Subscriptionplans::find result", [
        //     'subscription_plan_id_searched' => $request->subscription_plan,
        //     'plan_found' => $plan ? $plan->toArray() : null,
        // ]);

        if ($plan) {

            $startDate = Carbon::now();
            $expiresDate = $startDate->copy()->addDays($plan->duration_days);

            // \Log::info("[$traceId] Subscription dates calculated", [
            //     'starts_at' => $startDate->toDateTimeString(),
            //     'expires_at' => $expiresDate->toDateTimeString(),
            //     'duration_days' => $plan->duration_days,
            // ]);

            $organizationSubscription = OrganizationSubscriptions::where(
                'organization_id',
                $organization->id
            )->first();

            $subscriptionData = [
                'subscription_plan_id' => $plan->id,
                'starts_at'            => $startDate,
                'expires_at'           => $expiresDate,
                'monthly_photo_limit'  => $plan->monthly_photo_limit,
                'monthly_photo_used'   => 0,
            ];

            if ($organizationSubscription) {
                // \Log::info("[$traceId] Existing OrganizationSubscriptions found, updating", [
                //     'subscription_row_id' => $organizationSubscription->id,
                //     'data' => $subscriptionData,
                // ]);

                $subResult = $organizationSubscription->update($subscriptionData);

                // \Log::info("[$traceId] OrganizationSubscriptions update() returned:", ['result' => $subResult]);

            } else {
                // \Log::info("[$traceId] No existing OrganizationSubscriptions, creating new", [
                //     'data' => array_merge(['organization_id' => $organization->id], $subscriptionData),
                // ]);

                $newSubscription = OrganizationSubscriptions::create(array_merge(
                    ['organization_id' => $organization->id],
                    $subscriptionData
                ));

                // \Log::info("[$traceId] New OrganizationSubscriptions created", [
                //     'new_id' => $newSubscription->id ?? null,
                // ]);
            }
        } else {
            // \Log::warning("[$traceId] No Subscriptionplans row found for id: {$request->subscription_plan} — subscription table not touched");
        }

        DB::commit();

        // \Log::info("[$traceId] DB::commit() successful");

        // Verify from fresh DB read, not just in-memory
        $fresh = $organization->fresh();
        // \Log::info("[$traceId] Post-commit fresh() read from DB", [
        //     'subscription_plan_in_db' => $fresh->subscription_plan,
        // ]);

        ActivityLogger::log(
            'Update',
            'Organizations',
            'Updated organization: ' . $request->organization_name
        );

        // \Log::info("[$traceId] === updateOrganization END (success) ===");

        return redirect()->back()->with('success', 'Organization updated successfully!');

    } catch (\Exception $e) {

        DB::rollBack();

        // \Log::error("[$traceId] EXCEPTION during update — rolled back", [
        //     'message' => $e->getMessage(),
        //     'file'    => $e->getFile(),
        //     'line'    => $e->getLine(),
        //     'trace'   => $e->getTraceAsString(),
        // ]);

        return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

    // public function updateStatus(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required',
    //         'status' => 'required|in:-1,0,1'
    //     ]);

    //     $organization = Organization::findOrFail($request->id);
    //     if ($request->status == 1 && !$organization->plan) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Please assign a plan to this organization before activating it.'
    //     ]);
    //     }

    //     // ✅ Get old status before update
    //     $oldStatus = $organization->state;

    //     // ✅ Update status
    //     $organization->state = $request->status;
    //     $organization->save();

    //     // ✅ Convert status to readable text
    //     $statusText = [
    //         -1 => 'Deleted',
    //         0  => 'Inactive',
    //         1  => 'Active',
    //     ];

    //     // ✅ Activity Log
    //     ActivityLogger::log(
    //         'Update',
    //         'Organizations',
    //         'Changed status of ' . $organization->organization_name .
    //         ' from ' . ($statusText[$oldStatus] ?? $oldStatus) .
    //         ' to ' . ($statusText[$request->status] ?? $request->status)
    //     );

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Organization status updated successfully'
    //     ]);
    // }

    public function updateStatus(Request $request)
{
    $request->validate([
        'id' => 'required',
        'status' => 'required|in:-1,0,1'
    ]);

    $organization = Organization::findOrFail($request->id);

    // ✅ Fixed: 'plan' relation exist nahi karti, 'subscription' hai
    if ($request->status == 1 && !$organization->subscription) {
        return response()->json([
            'success' => false,
            'message' => 'Please assign a plan to this organization before activating it.'
        ]);
    }

    $oldStatus = $organization->state;
    $organization->state = $request->status;
    $organization->save();

    $statusText = [
        -1 => 'Deleted',
        0  => 'Inactive',
        1  => 'Active',
    ];

    ActivityLogger::log(
        'Update',
        'Organizations',
        'Changed status of ' . $organization->organization_name .
        ' from ' . ($statusText[$oldStatus] ?? $oldStatus) .
        ' to ' . ($statusText[$request->status] ?? $request->status)
    );

    return response()->json([
        'success' => true,
        'message' => 'Organization status updated successfully'
    ]);
}
}
