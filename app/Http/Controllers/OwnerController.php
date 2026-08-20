<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Helpers\DateTime;
use App\Helpers\ActivityLogger;
use Yajra\DataTables\DataTables;
use App\Models\PhotoDetail;
use App\Models\PhotoView;
use App\Models\Organization;
use App\Notifications\CommonMailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Models\Notifications;
use App\Models\EmployeeOtp;
use App\Services\SubscriptionService;
use Illuminate\Support\Str;
class OwnerController extends Controller
{
public function index()
{
    $id = Auth::id();

    $org_id = (int) Auth::user()->organization_id;

    /*
    |--------------------------------------------------------------------------
    | Total Employees
    |--------------------------------------------------------------------------
    */

    $users = User::where('state', '!=', -1)
        ->where('organization_id', $org_id)
        ->where('id', '!=', $id)
        ->orderBy('created_at', 'desc')
        ->get();

    $total_employees = $users->count();


    /*
    |--------------------------------------------------------------------------
    | Total Photos
    |--------------------------------------------------------------------------
    */

    $totalPhotos = PhotoDetail::whereHas('user', function ($query) use ($org_id) {
        $query->where('organization_id', $org_id);
    })->count();


    /*
    |--------------------------------------------------------------------------
    | Current Month Photos
    |--------------------------------------------------------------------------
    */

    $monthlyPhotos = PhotoDetail::whereHas('user', function ($query) use ($org_id) {
        $query->where('organization_id', $org_id);
    })
    ->whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->count();


    /*
    |--------------------------------------------------------------------------
    | Subscription / Monthly Limit
    |--------------------------------------------------------------------------
    */

    $org = Organization::with('subscription')->findOrFail($org_id);

    $monthlyPhotoLimit = $org->subscription->monthly_photo_limit ?? 0;
    $topupPhotoLimit = $org->subscription->topup_photo_limit ?? 0;
    $totalPhotoLimit = $monthlyPhotoLimit + $topupPhotoLimit;

    $remainingPhotos = max(
        0,
        $totalPhotoLimit - $monthlyPhotos
    );


    /*
    |--------------------------------------------------------------------------
    | Latest 10 Employees
    |--------------------------------------------------------------------------
    */

    $latestUsers = User::where('state', '!=', -1)
        ->where('organization_id', $org_id)
        ->where('id', '!=', $id)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Latest 10 Uploaded Photos
    |--------------------------------------------------------------------------
    */

    $latestPhotos = PhotoDetail::with('user')
        ->whereHas('user', function ($query) use ($org_id) {
            $query->where('organization_id', $org_id);
        })
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Monthly Photo Upload Count Per User
    |--------------------------------------------------------------------------
    |
    | Only users who uploaded at least one photo this month
    | will be included in the pie chart.
    |
    */

    $monthlyUploadedPhotos = PhotoDetail::with('user')
        ->whereHas('user', function ($query) use ($org_id) {
            $query->where('organization_id', $org_id);
        })
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->get();

    $photoUploadsByUser = $monthlyUploadedPhotos
        ->groupBy('user_id')
        ->map(function ($photos) {
            return [
                'name' => optional($photos->first()->user)->name ?? 'Unknown User',
                'count' => $photos->count(),
            ];
        })
        ->values();


    /*
    |--------------------------------------------------------------------------
    | Data for Chart.js
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| Data for Chart.js
|--------------------------------------------------------------------------
*/

// User names
$chartLabels = $photoUploadsByUser
    ->pluck('name')
    ->values()
    ->toArray();

// User upload counts
$chartData = $photoUploadsByUser
    ->pluck('count')
    ->values()
    ->toArray();

/*
|--------------------------------------------------------------------------
| Add Remaining Photos to Pie Chart
|--------------------------------------------------------------------------
*/

$remainingForChart = max(
    0,
    $totalPhotoLimit - $monthlyPhotos
);

if ($remainingForChart > 0) {

    $chartLabels[] = 'Remaining';

    $chartData[] = $remainingForChart;
}


    return view('owner.dashboard', compact(
        'total_employees',
        'totalPhotos',
        'monthlyPhotoLimit',
        'topupPhotoLimit',
        'totalPhotoLimit',
        'remainingPhotos',
        'monthlyPhotos',
        'latestUsers',
        'latestPhotos',
        'chartLabels',
        'chartData'
    ));
}
    public function index_bk()
    {
        $id = Auth::user()->id;
        $org_id =  (int)User::find(Auth::id())->organization_id;
        //$users = User::where('state', '!=', -1)->where('organization_id',$org_id)->where('id', '!=', $id)->orderBy('created_at', 'desc')->get();
        $users = User::where('state', '!=', -1)->where('organization_id',$org_id)->orderBy('created_at', 'desc')->get();
        $total_employees = count($users);
        $totalPhotos = PhotoDetail::whereHas('user', function ($query) use ($org_id) {
            $query->where('organization_id', $org_id);
                // ->where('state', '!=', -1);
        })->count();

        $monthlyPhotos = PhotoDetail::whereHas('user', function ($query) use ($org_id) {
         $query->where('organization_id', $org_id);
        //   ->where('state', '!=', -1);
        })
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->count();
        
        $org = Organization::with('subscription')->find($org_id);
        $monthlyPhotoLimit = $org->subscription->monthly_photo_limit;
        $remainingPhotos = max(0, $monthlyPhotoLimit - $monthlyPhotos);

        // dd(Auth::user()->getRoleNames());
         return view('owner.dashboard',compact('total_employees','totalPhotos','monthlyPhotoLimit','remainingPhotos','monthlyPhotos'));
    }

    public function ownerLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function profile(SubscriptionService $subscriptions)
    {
        $user = auth()->user();
        $organization = Organization::with('subscription')->find($user->organization_id);
         $subscription = $subscriptions->activeForOrganization($user->organization_id);
        $scheduledRenewals = $subscription
            ? $organization->subscriptions()->with('plan')
                ->where('state', true)
                ->where('starts_at', '>', $subscription->expires_at)
                ->orderBy('starts_at')->get()
            : collect();
        return view('owner.profile', compact('user','organization', 'subscription', 'scheduledRenewals'));
    }

     public function profileUpdate(Request $request)
    {
        
        $owner = Auth::guard('web')->user();

        // Validation
        $request->validate([
            // 'name'   => 'required|string|max:255',
            // 'number' => 'required|string|min:10|max:15',
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update fields
        $owner->name = $request->name;
        $owner->phone_number = $request->number;

        // If email is readonly, no need to update
        if ($request->filled('email')) {
            $owner->email = $request->email;
        }
        // Handle image upload
        if ($request->hasFile('image')) {

        // Delete old image
          if ($owner->profile_image && \Storage::disk('public')->exists($owner->profile_image)) {
                \Storage::disk('public')->delete($owner->profile_image);
            }

            $path = $request->file('image')->store('profiles', 'public');
            
            // Save only filename
            $owner->profile_image = $path;
        }

        
        $owner->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
        
    }

    public function changePassword(){
       
        return view('owner.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $admin = Auth::user(); // logged in admin/user
        if (!Hash::check($request->old_password, $admin->password)) {
            return back()->withErrors([
                'old_password' => 'Old password is incorrect'
            ]);
        }

        if (Hash::check($request->new_password, $admin->password)) {
            return back()->withErrors([
                'new_password' => 'New password must be different from old password'
            ]);
        }
        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return back()->with('success', 'Password changed successfully');
    }

    public function employees()
    {
        $orgId = (int) Auth::user()->organization_id;

        // Organization + subscription
        $org = Organization::with('subscription')->findOrFail($orgId);

        $monthlyPhotoLimit = $org->subscription->monthly_photo_limit ?? 0;
        $topupPhotoLimit = $org->subscription->topup_photo_limit ?? 0;
        $totalPhotoLimit = $monthlyPhotoLimit + $topupPhotoLimit;

        // Photos uploaded THIS MONTH
        $usedPhotos = PhotoDetail::whereHas('user', function ($query) use ($orgId) {
                $query->where('organization_id', $orgId);
            })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $remainingPhotos = max(0, $totalPhotoLimit - $usedPhotos);

        return view('owner.index', compact(
            'monthlyPhotoLimit',
            'topupPhotoLimit',
            'totalPhotoLimit',
            'usedPhotos',
            'remainingPhotos'
        ));
    }

    
    public function create(){
        return view('owner.add');
    }

   

    public function store(Request $request){
        $org_id =  (int)User::find(Auth::id())->organization_id;
        $organization = Organization::find($org_id); // ✅ ab organization define hai

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email',
            'phone_number' => 'nullable|numeric|digits_between:10,14',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'state' => 0, // Pending
            'password' => bcrypt('user123'), // temporary, activation ke baad khud change kar sakta hai
            'organization_id' => $org_id
        ]);

        $user->assignRole('employee');

        // ✅ Signed activation URL banao — 7 din tak valid, tamper-proof
        $activationUrl = URL::temporarySignedRoute(
            'owner.employee.activate',
            now()->addDays(7),
            ['id' => $user->id]
        );

       $slot = '
    <p>Dear <strong>'.$user->name.'</strong>,</p>

    <p>
        You have been invited to join
        <strong>'.$organization->organization_name.'</strong>
        on Photo Proof.
    </p>


    <h3>Account Details</h3>

    <p>
        <strong>Organization Name:</strong>
        '.$organization->organization_name.'
    </p>

    <p>
        <strong>Email:</strong>
        '.$user->email.'
    </p>

    <h3>How to Get Started</h3>

    <p>
        <strong>Step 1: Activate Your Account</strong><br>
        Click the <strong>Activate My Account</strong> button above
        and complete your account activation.
    </p>

    <p>
        <strong>Step 2: Download the App</strong><br>
        Download Photo Proof from the App Store or Google Play using
        the links below.
    </p>
    <p>
        <a href="'.config('app.app_urls.android', '#').'"
           style="color:#2563eb;">
            Download for Android
        </a>
        &nbsp;|&nbsp;
        <a href="'.config('app.app_urls.ios', '#').'"
           style="color:#2563eb;">
            Download for iOS
        </a>
    </p>

    <p>
        <strong>Step 3: Login as Corporate Login</strong><br>
        Open the Photo Proof app and select
        <strong>Login as Corporate Login</strong>.
        Enter your registered email address.
    </p>

    <p> <strong>Step 4: Use Your First Login OTP</strong><br> 
    After activating your account, a unique first-login OTP will be displayed on the account activation page. Use that OTP in the Photo Proof app to complete your first login. <strong>This OTP is valid for 20 minutes.</strong> </p>

    <p>
        <strong>Step 5: Start Using Photo Proof</strong><br>
        Once logged in, you can capture and upload photos through the
        Photo Proof app. Your photos will automatically be linked to
        your company account.
    </p>



    <h3>Activate Your Account</h3>

    <p>
        Please click the button below to activate your account:
    </p>

    <p>
        <a href="'.$activationUrl.'"
           style="background:#2563eb;color:#fff;padding:10px 20px;
                  text-decoration:none;border-radius:5px;">
            Activate My Account
        </a>
    </p>

    <p>
        Or copy this link into your browser:<br>
        <a href="'.$activationUrl.'"
           style="color:#2563eb;">
            '.$activationUrl.'
        </a>
    </p>


    <p>
        If you did not expect this invitation, please contact your
        company administrator.
    </p>
';

        try {
            Notification::route('mail', $user->email)
                ->notify(new CommonMailNotification(
                    'You are Invited - Activate Your Account',
                    $slot
                ));
        } catch (\Exception $e) {
            \Log::error('Employee Invitation Mail Error: ' . $e->getMessage());
        }

        ActivityLogger::log(
            'Create',
            'Employee',
            'Created new employee: ' . $request->email
        );

        return redirect()->back()->with('success', 'Employee invited successfully!');
    }
    public function activateEmployee(Request $request, $id)
    {
        $employee = User::find($id);

        if (!$employee) {
            return view('employee.activation-failed', [
                'message' => 'Invalid activation link.'
            ]);
        }

        // Already active
        if ($employee->state == 1) {
            return view('owner.employee.activation-email', [
                'employee' => $employee,
                'message' => 'Your account is already active.',
                'success' => true,
                'first_login_otp' => null,
            ]);
        }

        // Activate account
        $employee->state = 1;
        $employee->save();

        // Generate first-login OTP
        $otp = random_int(1000, 9999);

        // Remove any previous unused OTP
        EmployeeOtp::where('user_id', $employee->id)
            ->whereNull('verified_at')
            ->delete();

        // Save new OTP
        EmployeeOtp::create([
            'user_id'    => $employee->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(20),
        ]);

        ActivityLogger::log(
            'Update',
            'Employee',
            'Employee activated account: ' . $employee->email
        );

        return view('owner.employee.activation-email', [
            'employee' => $employee,
            'message' => 'Your account has been activated successfully!',
            'success' => true,
            'first_login_otp' => $otp,
        ]);
    }
    public function activateEmployeebk(Request $request, $id)
    {
        $employee = User::find($id);

        if (!$employee) {
            return view('employee.activation-failed', [
                'message' => 'Invalid activation link.'
            ]);
        }

        if ($employee->state == 1) {
            return view('owner.employee.activation-email', [
                'employee' => $employee,
                'message' => 'Your account is already active.',
                'success' => true
            ]);
        }

        $employee->state = 1;
        $employee->save();



        ActivityLogger::log(
            'Update',
            'Employee',
            'Employee activated account: ' . $employee->email
        );

        return view('owner.employee.activation-email', [
            'employee' => $employee,
            'message' => 'Your account has been activated successfully!',
            'success' => true
        ]);
    }

    public function list(Request $request)
    {
        $id = Auth::user()->id;
        $org_id =  (int)User::find(Auth::id())->organization_id;
       // $users = User::where('state', '!=', -1)->withCount('photos')->where('organization_id',$org_id)->where('id', '!=', $id)->orderBy('created_at', 'desc')->get();
        $users = User::where('state', '!=', -1)->withCount('photos')->where('organization_id',$org_id)->orderBy('created_at', 'desc')->get();
        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('profile_image', function ($user) {
                $default = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
                return '<img src="' . ($user->profile_image
                    ? asset('storage/' . $user->profile_image)
                    : $default) . '" width="40" height="40" class="rounded-circle">';
            })
        ->addColumn('name', function ($users) {
            return $users->name ?? '--'; // if device is null, show --
        })
        ->addColumn('email', function ($users) {
            return $users->email ?? '--'; // if device is null, show --
        })
        ->addColumn('phone_number', function ($users) {
            return $users->phone_number ?? '--'; // if device is null, show --
        })
        // ->addColumn('role', function ($user) {
        //     $roles = $user->getRoleNames();

        //     if ($roles->isEmpty()) {
        //         return '--';
        //     }

        //     return $roles->map(function ($role) {
        //         return '<span class="badge bg-info me-1">' . e(ucfirst($role)) . '</span>';
        //     })->implode(' ');
        // })
        ->addColumn('role', function ($user) {
            $roles = $user->getRoleNames();

            if ($roles->isEmpty()) {
                return '--';
            }

            if ($roles->contains('owner')) {
                return '<span class="badge bg-info me-1">Owner</span>';
            }

            return $roles->map(function ($role) {
                return '<span class="badge bg-info me-1">'
                    . e(ucfirst($role))
                    . '</span>';
            })->implode(' ');
        })
        ->rawColumns(['role'])
        ->addColumn('photo_count', function ($user) {
            return '<span class="badge bg-info" style="
                  font-size: 1.2rem; 
                  padding: 0.6em 1em; 
                  text-decoration: none; 
                  border-radius: 0.5rem;
                  display: inline-block;
              " ><a href="'.route('owner.employee.show.imagedata', $user->id).'" class="badge bg-info">
             '.$user->photos_count.'
            </a></span>';
        })
        ->addColumn('status', function ($users) {
                if (!$users->hasRole('owner')) {
                    if ($users->state == 1) {
                        return '<button class="btn btn-sm btn-success toggle-status" data-id="'.$users->id.'" data-status="0">Active</button>';
                    }
                    if ($users->state == 0) {
                        return '<button class="btn btn-sm btn-warning toggle-status" data-id="'.$users->id.'" data-status="1">Pending</button>';
                        
                    }
                    return '<span class="badge bg-danger">Deleted</span>';
                }
                else{
                return '-';
               }
        })
        ->addColumn('actions', function ($users) {
            // return '<a href="'.route('admin.users.show.data', $admins->id).'" class="btn btn-sm btn-primary">View</a>
            //         <a href="'.route('admin.users.edit.data', $admins->id).'" class="btn btn-sm btn-warning">Edit</a>
            //         <button class="btn btn-sm btn-danger delete-user" data-id="'.$admins->id.'">Delete</button>';
               if (!$users->hasRole('owner')) {
                return '<a href="'.route('owner.employee.edit.data', $users->id).'" class="btn btn-sm btn-warning">Edit</a>
                       ';
                        // <button class="btn btn-sm btn-danger delete-user" data-id="'.$users->id.'">Delete</button>
               }else{
                return '-';
               }
            
        }) 
        ->rawColumns(['profile_image', 'role', 'status', 'photo_count', 'actions'])
        ->make(true);
        
    }

    public function editEmployee(Request $request,$user_id)
    {
        $user = User::findOrFail($user_id);
        return view('owner.edit',compact('user'));
    }

    public function updateEmployee(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone_number'        => 'required|numeric|digits_between:10,14',
        ]);

        // Capture old data BEFORE updating
        $oldData = $user->only(['name', 'phone_number']);

        // Update fields
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;

        $user->save();

        $changes = [];
        foreach (['name', 'phone_number'] as $field) {
            if (($oldData[$field] ?? null) !== ($user->$field ?? null)) {
                $old = $oldData[$field] ?? '-';
                $new = $user->$field ?? '-';
                $changes[] = ucfirst(str_replace('_', ' ', $field)) . " changed from '$old' to '$new'";
            }
        }

        if (!empty($changes)) {
            $description = implode('; ', $changes);
            
            ActivityLogger::log(
                'Update',
                'Employee Users',
                $description
            );
        }

        return redirect()->back()->with('success', 'Employee Updated Successfully!');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required|in:-1,0,1'
        ]);

         $user = User::findOrFail($request->id);

        // ✅ Get old status before update
        $oldStatus = $user->state;

        // ✅ Update status
        $user->state = $request->status;
        $user->save();

        // ✅ Convert status to readable text
        $statusText = [
            -1 => 'Deleted',
            0  => 'Inactive',
            1  => 'Active',
        ];

        // ✅ Activity Log
        ActivityLogger::log(
            'Update',
            'Employee',
            'Changed status of ' . $user->name .
            ' from ' . ($statusText[$oldStatus] ?? $oldStatus) .
            ' to ' . ($statusText[$request->status] ?? $request->status)
        );

        return response()->json([
            'success' => true,
            'message' => 'Organization status updated successfully'
        ]);
    }

    public function updatephotoStatus(Request $request){
    $id = $request->input('id');
    $status = $request->input('state');
//    dd($id,$status);
     $admin = Auth::user();
    // dd($id,$status);
        $request->validate([
            'id' => 'required',
            'state' => 'required|in:-1,0,1'
        ]);

        $photo = PhotoDetail::findOrFail($id);
        $oldStatus = $photo->state;
        $photo->state = $status;
        $photo->save();
         $statusText = [
            -1 => 'Deleted',
            0  => 'Inactive',
            1  => 'Active',
        ];

        // ✅ Activity Log
        ActivityLogger::log(
            'Update',
            'Employee Photos',
            'Changed status of photo ' . $photo->name .
            ' from ' . ($statusText[$oldStatus] ?? $oldStatus) .
            ' to ' . ($statusText[$status] ?? $status)
        );
        return response()->json([
            'success' => true,
            'message' => 'Photo status updated successfully'
        ]);
    }

    public function employeePhotos(Request $request)
    {
        $id = Auth::user()->id;
        return view('owner.photo.photos');
    }

    public function employeePhotosList(Request $request){
    $org_id = (int) User::find(Auth::id())->organization_id;

        $photos = PhotoDetail::with([
            'user.photo_upload_tracks',
            'user.organization'
        ])
        ->whereHas('user', function ($query) use ($org_id) {
            $query->where('organization_id', $org_id);
        })
        ->orderBy('created_at', 'desc')
        ->where('state', '!=', -1)
        ->get();

    return DataTables::of($photos)
        ->addIndexColumn()
        ->addColumn('photo', function ($photo) {
            $thumb = $photo->thumbnail ? $photo->thumbnail : $photo->photo;

            $image = $thumb
                ? asset('storage/'.$thumb)
                : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';

            return '<a href="'.route('owner.photos.show', $photo->id).'">
                        <img src="'.$image.'" width="80" height="80" style="border-radius:5px;">
                    </a>';
        })
        ->addColumn('random_id', fn($photo) => $photo->random_id ?? '-')
        ->addColumn('name', function ($photo) {
            return $photo->user ? $photo->user->name : '-';
        })
        ->addColumn('location', fn($photo) => $photo->location ?? '-')
        ->addColumn('user_name', function ($photo) {
            return $photo->user ? $photo->user->email : '--';
        })
        ->addColumn('organization_name', function ($photo) {
            return ($photo->user && $photo->user->organization)
                ? $photo->user->organization->organization_name
                : '--';
        })
        ->addColumn('created_at', function ($photo) {
            return DateTime::dateFormat($photo->created_at) ?? '-';
        })
        ->addColumn('view_count', function ($photo) {
            $count = $photo->view_count ?? 0;
            return '<span class="badge bg-info" style="
                font-size: 1.2rem; 
                padding: 0.6em 1em; 
                text-decoration: none; 
                border-radius: 0.5rem;
                display: inline-block;
            "><a href="'.route('owner.photos.show', $photo->id).'" class="badge bg-info">
                '.$count.'
            </a></span>';
        })
        ->addColumn('status', function ($photo) {
                if ($photo->state == 1) {
                    return '<button class="btn btn-sm btn-success toggle-state" data-id="'.$photo->id.'" data-state="0">Active</button>';
                }
                if ($photo->state == 0) {
                    return '<button class="btn btn-sm btn-warning toggle-state" data-id="'.$photo->id.'" data-state="1">Inactive</button>';
                }
                if ($photo->state == -1) {
                    return '<span style="color: red;">Deleted</span>';
                }
        })
        ->addColumn('upload_track_record', function ($row) {
            return '<button class="btn btn-sm btn-primary viewTrackBtn">View Track</button>';
        })
        ->rawColumns(['photo', 'status', 'view_count', 'upload_track_record'])
        ->make(true);
}


    public function show(Request $request,$id){
        $photos = PhotoDetail::with('user')->find($id);
        $count = $photos->view_count;
        return view('owner.photo.photo_data',compact('id','count'));
    }

    public function showdata(Request $request,$id)
    {
        $photoViews = PhotoView::where('photo_detail_id', $id)
                    ->with('photo')
                    ->orderBy('created_at','desc')
                    ->get();

    
        return DataTables::of($photoViews)
            ->addIndexColumn()
            ->addColumn('ip_address', function ($photoViews) {
                return $photoViews->ip_address ?? '-';
            })

        ->addColumn('browser', function ($photoViews) {
            return $photoViews->browser ?? '-';
        })

        ->addColumn('platform', function ($photoViews) {
            return $photoViews->platform ?? '-';
        })

        ->addColumn('device', function ($photoViews) {
            return $photoViews->device ?? '-';
        })
        
        ->addColumn('device_type', function ($photoViews) {
            return $photoViews->device_type ?? '-';
        })

        ->addColumn('referer', function ($photoViews) {
            return $photoViews->referer ?? '-';
        })

        ->addColumn('user_agent', function ($photoViews) {
            return $photoViews->user_agent ?? '-';
        })
        ->addColumn('country', function ($photoViews) {
            return $photoViews->country ?? '-';
        })

        ->addColumn('country_code', function ($photoViews) {
            return $photoViews->country_code ?? '-';
        })

        ->addColumn('region', function ($photoViews) {
            return $photoViews->region ?? '-';
        })
        
        ->addColumn('region_name', function ($photoViews) {
            return $photoViews->region_name ?? '-';
        })

        ->addColumn('city', function ($photoViews) {
            return $photoViews->city ?? '-';
        })

        ->addColumn('zip', function ($photoViews) {
            return $photoViews->zip ?? '-';
        })
        ->addColumn('latitude', function ($photoViews) {
            return $photoViews->latitude ?? '-';
        })->addColumn('longitude', function ($photoViews) {
            return $photoViews->longitude ?? '-';
        })

        ->addColumn('timezone', function ($photoViews) {
            return $photoViews->timezone ?? '-';
        })
        
        ->addColumn('isp', function ($photoViews) {
            return $photoViews->isp ?? '-';
        })

        ->addColumn('org', function ($photoViews) {
            return $photoViews->org ?? '-';
        })

        ->addColumn('as_name', function ($photoViews) {
            return $photoViews->as_name ?? '-';
        })
        ->addColumn('created_at', function ($photoViews) {
            return DateTime::dateFormat($photoViews->created_at) ?? '-';
        })
        ->rawColumns(['photoViews'])
        ->make(true);
    }  

    public function showImagedatawithid(Request $request,$id){
            $user = User::findOrFail($request->id);
            return view('owner.employee.showphotos',compact('user'));
    }

    public function getUsersWithImageswithId(Request $request, $id)
    {
        $users = User::with('photos.uploadTrack')
            ->when($request->name, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->when($id, function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->get();
        $data = [];
        $serialNumber = 1;

        foreach ($users as $user) {
            foreach ($user->photos as $photo) {

                $track = $photo->uploadTrack;

                $data[] = [
                    'name'=>$user->name,
                    'created_at'=>DateTime::dateFormat($photo->created_at),
                    'email'=>$user->email,
                    'random_id' => $photo->random_id,
                    'serial_number' => $serialNumber++,
                    'photo_id' => $photo->id,
                    'user_email' => $user->email,
                    'view_count' => $photo->view_count ?? 0,
                    'image' => $photo->photo ? asset('storage/' . $photo->photo) : '',
                    'date_time' => DateTime::dateFormat($photo->word_api_date_time) ?? '',
                    'location' => $photo->location ?? '',
                    // 'country' => isset($photo->country) ? $photo->country : (isset($track->country) ? $track->country : ''),
                    // 'region' => isset($photo->region_name) ? $photo->region_name : (isset($track->region_name) ? $track->region_name : ''),
                    // 'city' => isset($photo->city) ? $photo->city : (isset($track->city) ? $track->city : ''),
                    // 'zip' => isset($photo->zip) ? $photo->zip : (isset($track->zip) ? $track->zip : ''),
                    'timezone' => isset($photo->timezone) ? $photo->timezone : (isset($track->timezone) ? $track->timezone : ''),
                    'latitude' => isset($photo->latitude) 
                        ? number_format($photo->latitude, 8, '.', '') 
                        : (isset($track->latitude) ? number_format($track->latitude, 8, '.', '') : null),

                    'longitude' => isset($photo->longitude) 
                        ? number_format($photo->longitude, 8, '.', '') 
                        : (isset($track->longitude) ? number_format($track->longitude, 8, '.', '') : null),
                    'ip_address' => $track->ip_address ?? '',
                    'device_type' => $photo->device_type ?? '',
                    'device_brand' => $photo->device_brand ?? '',
                    'device_model' => $photo->device_model ?? '',
                    'device_name' => $photo->device_name ?? '',
                    'device_manufacturer' => $photo->device_manufacturer ?? '',
                    'android_version' => $photo->android_version ?? '',
                    'android_sdk' => $photo->android_sdk ?? '',
                    'ios_system_version' => $photo->ios_system_version ?? '',
                    'ios_identifier' => $photo->ios_identifier ?? '',
                    'isp' => $track->isp ?? '',
                    'state' => $photo->state ?? '',
                    
                ];
            }
        }

       return DataTables::of($data)
            ->addColumn('images', function ($row) {
                 $default = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
                // return $row['image']
                //     ? '<img src="' . $row['image'] . '" width="80" height="80" style="border-radius:5px;">'
                //     :  $default;
                  return '<a href="'.route('owner.photos.show', $row['photo_id']).'">
                        <img src="'.$row['image'].'" width="80" height="80" style="border-radius:5px;">
                    </a>';
    //             return $row['image']
    // ? '<button class="btn btn-sm btn-primary viewTrackBtn" style="padding:0; border:none; background:none;">
    //         <img src="' . $row['image'] . '" width="80" height="80" style="border-radius:5px;">
    //    </button>'
    // : 'No Image';
            })
            ->addColumn('view_count', function ($row) {
                return '<span class="badge bg-info" style="
                        font-size: 1.2rem; 
                        padding: 0.6em 1em; 
                        text-decoration: none; 
                        border-radius: 0.5rem;
                        display: inline-block;
                    "><a href="'.route('owner.photos.show',  $row['photo_id']).'" class="badge bg-info">
                    '.$row['view_count'].'
                    </a></span>';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-primary viewTrackBtn">View Track</button>';
            })
            ->addColumn('status', function ($row) {
            if ($row['state'] == 1) {
                    return '<button class="btn btn-sm btn-success toggle-state" data-id="'.$row['photo_id'].'" data-state="0">Active</button>';
            }
            if ($row['state'] == 0) {
                    return '<button class="btn btn-sm btn-warning toggle-state" data-id="'.$row['photo_id'].'" data-state="1">Inactive</button>';
                    
            }
            })
            ->rawColumns(['images', 'action', 'view_count','status']) // 👈 FIX HERE
            ->make(true);
        }

        public function notifications(){
             return view('owner.notifications.index');
        }


    public function notificationsList(Request $request)
{
    $user_data =  User::find(Auth::id());
    $org_id = (int) $user_data->organization_id;
    
    $notifications = Notifications::query()
        ->where('organization_id', $org_id); // ✅ direct filter, no join needed

    if ($request->filled('notification_type')) {
        $notifications->where('type', $request->notification_type);
    }

    $notifications = $notifications
        ->where('state', '!=', -1)
        ->orderBy('created_at', 'desc')
        ->get();
    return DataTables::of($notifications)
        ->addIndexColumn()
        ->setRowClass(function ($notifications) {
            return $notifications->is_read == 0 ? 'custom-unread-row' : 'custom-read-row';
        })
        ->addColumn('image', function ($notifications) {
            $photo = PhotoDetail::where('random_id', $notifications->photo_random_id)->first();
            $default = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
            $image = $photo
                ? asset('storage/' . ($photo->thumbnail ?: $photo->photo))
                : $default;
            return '<img src="'.$image.'" width="40" height="40" class="rounded-circle">';
        })
        ->addColumn('photo_random_id', function ($notifications) {
            return $notifications->photo_random_id ?? '--';
        })
        ->addColumn('name', function ($notifications) {
            return $notifications->name ?? '-';
        })
        ->addColumn('email', function ($notifications) {
            return $notifications->email ?? '-';
        })
        ->addColumn('message', function ($notifications) {
            $data = json_decode($notifications->data, true);
            $message = $data['message'] ?? null;
            return $message ? Str::limit($message, 100, '...') : '--';
        })
        ->addColumn('type', function ($notifications) {
            return ucwords($notifications->type) ?? '--';
        })
        ->addColumn('ip_address', function ($notifications) {
            $data = json_decode($notifications->data, true);
            return $data['ip'] ?? '-';
        })
        ->addColumn('date', function ($notifications) {
            return DateTime::dateFormat($notifications->created_at);
        })
        ->addColumn('actions', function ($notifications) {
            $data = json_decode($notifications->data, true);
            $ip = $data['ip'] ?? '';
            $message_data = $data['message'] ?? null;
            $message = $message_data ? Str::limit($message_data, 100, '...') : '--';
            $browser = $data['browser'] ?? '';
            $platform = $data['platform'] ?? '';
            $deviceType = $data['deviceType'] ?? '';

            if (!empty($data['country']) && !empty($data['region']) && !empty($data['city']) && !empty($data['zip'])) {
                $location = implode(',', [$data['country'], $data['region'], $data['city'], $data['zip']]);
            } else {
                $location = '';
            }

            // $createOrganizationButton = '';
            // if ($notifications->type == 'Contact us') {
            //     $createOrganizationButton = '
            //         <a href="'.url('/admin/organization/create').'?name='.urlencode($notifications->name).'&email='.urlencode($notifications->email).'"
            //         class="btn btn-success btn-sm ms-1">
            //             Create Organization
            //         </a>';
            // }

            $photo = PhotoDetail::where('random_id', $notifications->photo_random_id)->first();
            $default = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
            $image = $photo ? asset('storage/' . ($photo->thumbnail ?: $photo->photo)) : $default;
            
            return '
                <button
                    class="btn btn-primary btn-sm ownerviewNotification"
                    data-id="'.$notifications->id.'"
                    data-name="'.$notifications->name.'"
                    data-email="'.$notifications->email.'"
                    data-message="'.$message.'"
                    data-image="'.$image.'",
                    data-ip="'.$ip.'"
                    data-type="'.ucwords($notifications->type).'"
                    data-date="'.DateTime::dateFormat($notifications->created_at).'"
                    data-browser="'.$browser.'"
                    data-platform="'.$platform.'"
                    data-devicetype="'.$deviceType.'"
                    data-location="'.$location.'"
                    data-bs-toggle="modal"
                    data-bs-target="#ownerNotificationModal">
                    View
                </button>
                ';
        })
        ->addColumn('notification', function ($notifications) {
            $data = json_decode($notifications->data, true) ?: [];
            $notificationUser = User::where('email', $notifications->email)->first();
            $photo = PhotoDetail::where('random_id', $notifications->photo_random_id)->first();
            $photoUrl = $photo ? asset('storage/' . ($photo->thumbnail ?: $photo->photo)) : '';
            $avatarUrl = $notificationUser && $notificationUser->profile_image ? asset('storage/' . $notificationUser->profile_image) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
            $name = $notifications->name ?: 'Someone';
            $message = $data['message'] ?? 'New activity was recorded.';
            $type = ucwords($notifications->type ?: 'Notification');
            $date = DateTime::dateFormat($notifications->created_at);
            $ip = $data['ip'] ?? '';
            $browser = $data['browser'] ?? '';
            $platform = $data['platform'] ?? '';
            $deviceType = $data['deviceType'] ?? '';
            $location = !empty($data['country']) && !empty($data['region']) && !empty($data['city']) && !empty($data['zip'])
                ? implode(', ', [$data['country'], $data['region'], $data['city'], $data['zip']])
                : '';
            $photoThumbnail = $photoUrl
                ? '<img src="'.e($photoUrl).'" alt="Related photo" class="notification-photo">'
                : '';
            $unreadClass = $notifications->is_read == 0 ? ' is-unread' : '';

            return '<article class="owner-notification-item'.$unreadClass.'">
                <img src="'.e($avatarUrl).'" alt="'.e($name).'" class="notification-avatar">
                <div class="notification-copy">
                    <div class="notification-message">'.e($name).' '.e($type).'</div>
                    <time class="notification-date">'.e($date).'</time>
                </div>
                <div class="notification-side">
                    '.$photoThumbnail.'
                    <button type="button" class="notification-view-btn ownerviewNotification"
                        data-id="'.e($notifications->id).'"
                        data-name="'.e($name).'"
                        data-email="'.e($notifications->email).'"
                        data-message="'.e($message).'"
                        data-image="'.e($photoUrl).'"
                        data-ip="'.e($ip).'"
                        data-type="'.e($type).'"
                        data-date="'.e($date).'"
                        data-browser="'.e($browser).'"
                        data-platform="'.e($platform).'"
                        data-devicetype="'.e($deviceType).'"
                        data-location="'.e($location).'"
                        data-bs-toggle="modal" data-bs-target="#ownerNotificationModal">View details</button>
                </div>
            </article>';
        })
        ->rawColumns(['image','name','actions','message','email','type','ip_address','date','notification'])
        ->make(true);
}


public function getUnreadNotifications()
{
    $org_id = (int) User::find(Auth::id())->organization_id;

    $notifications = Notifications::where('state', '!=', -1)
        ->where('is_read', 0)
        ->where('organization_id', $org_id) // ✅ direct
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($item) {
            $item->created_at_formatted = $item->created_at
                ? DateTime::dateFormat($item->created_at)
                : '--';
            return $item;
        });

    return response()->json($notifications);
}

public function markAsRead($id)
{
    $org_id = (int) User::find(Auth::id())->organization_id;

    $notification = Notifications::where('organization_id', $org_id) // ✅ direct
        ->findOrFail($id);

    $notification->is_read = 1;
    $notification->save();

    return response()->json(['status' => 'success']);
}


public function unreadCount(Request $request,$id){
   
 $notification = Notifications::findOrFail($id);
  
    $notification->is_read = 1;
    $notification->save();
    $newCount = Notifications::where('is_read', false)
                            ->orderBy('created_at', 'desc')
                            // ->take(5)
                            ->count();  // Count unread notifications
    return response()->json(['newCount' => $newCount]);
}








}
