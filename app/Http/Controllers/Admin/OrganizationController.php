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
class OrganizationController extends Controller
{
    public function index(){
       

        return view('admin.organization.index');
    }

     public function list(Request $request){

    $organizations = Organization::where('state', '!=', -1)->orderBy('created_at', 'desc')->get();
    return DataTables::of($organizations)
        ->addIndexColumn()
        ->addColumn('organization_name', function ($organizations) {
            return $organizations->organization_name ?? '--';
        })
        ->addColumn('organization_email', function ($organizations) {
            return $organizations->organization_email ?? '--'; // if device is null, show --
        })

         ->addColumn('organization_code', function ($organizations) {
            return $organizations->organization_code ?? '--'; // if device is null, show --
        })

         ->addColumn('plan', function ($organizations) {
            return $organizations->subscription_plan ?? '--'; // if device is null, show --
        })
       

         ->addColumn('limit', function ($organizations) {
            return $organizations->mobile_number ?? '--'; // if device is null, show --
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
            return DateTime::dateFormat($organizations->created_at) ?? '--'; // if device is null, show --
        })
        ->addColumn('actions', function ($organizations) {
            // return '<a href="'.route('admin.users.show.data', $organizations->id).'" class="btn btn-sm btn-primary">View</a>
            //         <a href="'.route('admin.users.edit.data', $organizations->id).'" class="btn btn-sm btn-warning">Edit</a>
            //         <button class="btn btn-sm btn-danger delete-user" data-id="'.$organizations->id.'">Delete</button>';
             return '
             <a href="'.route('admin.organization.show.data', $organizations->id).'" class="btn btn-sm btn-primary">View</a>
             <a href="'.route('admin.organization.edit.data', $organizations->id).'" class="btn btn-sm btn-warning">Edit</a>
             <button class="btn btn-sm btn-danger delete-user" data-id="'.$organizations->id.'">Delete</button>';
            
        })
        ->rawColumns(['status', 'actions'])
        ->make(true);
        
    }

    public function create(){
        $allPlans = Subscriptionplans::all();
        return view('admin.organization.add',compact('allPlans'));
    }

    // public function addOrganization(Request $request){
    //    $request->validate([
    //     'organization_name'   => 'required|string|max:255',
    //     // 'business_type' => 'required|string|max:255',
    //     // 'owner_name' => 'required|string|max:255',
    //     'organization_email'  => 'required|email|unique:organizations,organization_email',
    //     'mobile_number' => 'numeric|digits_between:10,14',
    //     'password' => 'required|min:6',
    //     // 'subscription_plan' => 'required|string|max:255'
    //     ]);
    //     $organization =Organization::create([
    //         'organization_name' => $request->organization_name,
    //         'business_type' => $request->business_type,
    //         'owner_name' => $request->owner_name,
    //         'organization_email' => $request->organization_email,
    //         'organization_code'=>'',
    //         'mobile_number' => $request->mobile_number,
    //         'password' => Hash::make($request->password),
    //         'subscription_plan' => $request->subscription_plan,
    //         'created_by' => Auth::user()->id ?? null,
        
    //     ]);
    //     $organization->organization_code = 'ORG_' . $organization->id;
    //     $organization->save();
        
    //     $slot = '
    //     <p>Dear <strong>'.$organization->owner_name.'</strong>,</p>

    //     <p>Welcome! Your organization has been created successfully.</p>

    //     <hr>

    //     <h3>Login Details</h3>

    //     <p><strong>Login URL:</strong>
    //         <a href="'.url('/login').'">'.url('/login').'</a>
    //     </p>

    //     <p><strong>Username / Email:</strong> '.$organization->organization_email.'</p>

    //     <p><strong>Temporary Password:</strong> '.$request->password.'</p>

    //     <p><strong>Organization Name:</strong> '.$organization->organization_name.'</p>

    //     <p><strong>Organization Code:</strong> '.$organization->organization_code.'</p>

    //     <p><strong>Subscription Plan:</strong> '.$organization->subscription_plan.'</p>

    //     <hr>

    //     <h3>Next Steps</h3>

    //     <ul>
    //         <li>Log in using the credentials above.</li>
    //         <li>Change your password after your first login.</li>
    //         <li>Invite your employees from the dashboard.</li>
    //         <li>Assign roles and permissions to your employees.</li>
    //     </ul>

    //     <p>If you have any questions, please contact our support team.</p>

    //     <p>Thank you,<br>Your Team</p>';

    //     Notification::route('mail', $organization->organization_email)
    //     ->notify(new CommonMailNotification(
    //         'Welcome to Our Portal - Account Created Successfully',
    //         $slot
    //     ));
    //     ActivityLogger::log(
    //         'Create',
    //         'Organizations',
    //         'Created new organization: ' . $request->organization_name
    //     );
    //     return redirect()->back()->with('success', 'Organization added successfully!');

    // }

    public function addOrganization(Request $request)
    {
        $request->validate([
            'organization_name'   => 'required|string|max:255',
            'organization_email'  => 'required|email|unique:users,email',
            'mobile_number' => 'numeric|digits_between:10,14',
            'password' => 'required|min:6',
        ]);

        DB::beginTransaction();
        try {
            $organization = Organization::create([
                'organization_name' => $request->organization_name,
                'business_type'     => $request->business_type,
                'organization_code' => '',
                'subscription_plan' => $request->subscription_plan,
                'message'           => $request->message,
                'created_by'        => Auth::user()->id,
            ]);

            $organization->organization_code = 'ORG_' . $organization->id;
            $organization->save();

            // 2. Create User (email, password, org_id here)
            $user = User::create([
                'name'            => $request->owner_name, 
                'email'           => $request->organization_email,
                'password'        => Hash::make($request->password),
                'phone_number'   => $request->mobile_number,
                'organization_id' => $organization->id,
            ]);
            
            $user->assignRole(['owner', 'employee']);
            $id = $request->subscription_plan;
            $getPlanDataById = Subscriptionplans::find($id);
            $startDate = Carbon::now();
            $expiresDate = $startDate->copy()->addDays($getPlanDataById->duration_days);
            $OrganizationSubscriptions = OrganizationSubscriptions::create([
                'organization_id'            => $organization->id, 
                'subscription_plan_id'           => $request->subscription_plan,
                'starts_at'        => $startDate,
                'expires_at'   => $expiresDate,
                'monthly_photo_limit' => $getPlanDataById->monthly_photo_limit,
                'monthly_photo_used' => 30,
                'max_employees' => $getPlanDataById->max_employees,
            ]);
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage()); 
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }

        $slot = '
        <p>Dear <strong>'.$user->name.'</strong>,</p>

        <p>Welcome! Your organization has been created successfully.</p>

        <hr>

        <h3>Login Details</h3>

        <p><strong>Login URL:</strong>
            <a href="'.url('/login').'">'.url('/login').'</a>
        </p>

        <p><strong>Username / Email:</strong> '.$user->email.'</p>

        <p><strong>Temporary Password:</strong> '.$request->password.'</p>

        <p><strong>Organization Name:</strong> '.$organization->organization_name.'</p>

        <p><strong>Organization Code:</strong> '.$organization->organization_code.'</p>

        <p><strong>Subscription Plan:</strong> '.$organization->subscription_plan.'</p>

        <hr>

        <h3>Next Steps</h3>

        <ul>
            <li>Log in using the credentials above.</li>
            <li>Change your password after your first login.</li>
            <li>Invite your employees from the dashboard.</li>
            <li>Assign roles and permissions to your employees.</li>
        </ul>

        <p>If you have any questions, please contact our support team.</p>

        <p>Thank you,<br>Your Team</p>';
        
        $admin_email = env('ADMIN_EMAIL');
        // Notification::route('mail', [$user->email, $admin_email])
        //     ->notify(new CommonMailNotification(
        //         'Welcome to Our Portal - Account Created Successfully',
        //         $slot
        //     ));
        try {
        Notification::route('mail', [$user->email, $admin_email])
                ->notify(new CommonMailNotification(
                    'Welcome to Our Portal - Account Created Successfully',
                    $slot
                ));
        } catch (\Exception $e) {
            dd('Mail Error: ' . $e->getMessage());
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

public function updateOrganization(Request $request, $id)
{

    $organization = Organization::find($id);

    if (!$organization) {
        return redirect()->back()->with('error', 'Organization not found.');
    }
    $request->validate([
        'organization_name'  => 'required|string|max:255',
        'mobile_number'      => 'nullable|numeric|digits_between:10,14',
        'password'           => 'nullable|min:6',
    ]);

    DB::beginTransaction();

    try {

        // Update Organization
        $organization->update([
            'business_type'     => $request->business_type,
            'subscription_plan' => $request->subscription_plan,
            'message'           => $request->message,
        ]);

        // Update Owner User
        $user = User::where('organization_id', $organization->id)->first();

        if ($user) {

            $userData = [
                'name'          => $request->owner_name,
                'email'         => $request->organization_email,
                'phone_number'  => $request->mobile_number,
            ];


            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }


            $user->update($userData);
         
        }


        // Subscription Plan
        $plan = Subscriptionplans::find($request->subscription_plan);

        if ($plan) {

            $startDate = Carbon::now();

            $expiresDate = $startDate->copy()
                ->addDays($plan->duration_days);
            $organizationSubscription = OrganizationSubscriptions::where(
                'organization_id',
                $organization->id
            )->first();


            if ($organizationSubscription) {

                $organizationSubscription->update([
                    'subscription_plan_id' => $plan->id,
                    'starts_at'            => $startDate,
                    'expires_at'           => $expiresDate,
                    'monthly_photo_limit'  => $plan->monthly_photo_limit,
                    'monthly_photo_used'   => 0,
                    'max_employees'        => $plan->max_employees,
                ]);

            } else {

                $newSubscription = OrganizationSubscriptions::create([
                    'organization_id'       => $organization->id,
                    'subscription_plan_id'  => $plan->id,
                    'starts_at'             => $startDate,
                    'expires_at'            => $expiresDate,
                    'monthly_photo_limit'  => $plan->monthly_photo_limit,
                    'monthly_photo_used'   => 0,
                    'max_employees'        => $plan->max_employees,
                ]);
            }
        }

        DB::commit();

    } catch (\Exception $e) {

        DB::rollBack();
    }

      ActivityLogger::log(
            'Update',
            'Organizations',
            'Updated organization: ' . $request->organization_name
        );

        return redirect()->back()->with('success', 'Organization updated successfully!');


    
}

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required|in:-1,0,1'
        ]);

        $organization = Organization::findOrFail($request->id);

        // ✅ Get old status before update
        $oldStatus = $organization->state;

        // ✅ Update status
        $organization->state = $request->status;
        $organization->save();

        // ✅ Convert status to readable text
        $statusText = [
            -1 => 'Deleted',
            0  => 'Inactive',
            1  => 'Active',
        ];

        // ✅ Activity Log
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
