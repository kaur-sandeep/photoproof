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
class OwnerController extends Controller
{
    public function index()
    {
        $id = Auth::user()->id;
        $org_id =  (int)User::find(Auth::id())->organization_id;
        $users = User::where('state', '!=', -1)->where('organization_id',$org_id)->where('id', '!=', $id)->orderBy('created_at', 'desc')->get();
        $total_employees = count($users);

        // dd(Auth::user()->getRoleNames());
         return view('owner.dashboard',compact('total_employees'));
    }

    public function ownerLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function profile()
    {
        $user = auth()->user();
        
        return view('owner.profile', compact('user'));
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
            if ($owner->profile_image && Storage::disk('public')->exists('profile/' . $owner->profile_image)) {
                Storage::disk('public')->delete('profile/' . $owner->profile_image);
            }

            // Store image (auto generate name)
            $path = $request->file('image')->store('profile', 'public');

            // Save only filename
            $owner->profile_image = basename($path);
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

    public function employees(){
         return view('owner.index');
    }

    
    public function create(){
        return view('owner.add');
    }

    public function store(Request $request){
       $id = Auth::user()->id;
       $org_id =  (int)User::find(Auth::id())->organization_id;
     
        $request->validate([
        'name'   => 'required|string|max:255',
        'email'  => 'required|email|unique:users,email',
        'phone_number' => 'required|numeric|digits_between:10,14',
        ]);
       $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'state'=> 0,
            'password' => bcrypt('user123'),
            'organization_id'=>$org_id
        ]);
        $user->assignRole('employee');
        ActivityLogger::log(
            'Create',
            'Employee',
            'Created new employee: ' . $request->email
        );
        $user->assignRole('employee');
        return redirect()->back()->with('success', 'User added successfully!');

    }

    public function list(Request $request)
    {
        $id = Auth::user()->id;
        $org_id =  (int)User::find(Auth::id())->organization_id;
        $users = User::where('state', '!=', -1)->where('organization_id',$org_id)->where('id', '!=', $id)->orderBy('created_at', 'desc')->get();
        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('name', function ($users) {
            return $users->name ?? '--'; // if device is null, show --
        })
        ->addColumn('email', function ($users) {
            return $users->email ?? '--'; // if device is null, show --
        })
        ->addColumn('phone_number', function ($users) {
            return $users->phone_number ?? '--'; // if device is null, show --
        })
        ->addColumn('status', function ($users) {
                if ($users->state == 1) {
                    return '<button class="btn btn-sm btn-success toggle-status" data-id="'.$users->id.'" data-status="0">Active</button>';
                }
                if ($users->state == 0) {
                    return '<button class="btn btn-sm btn-warning toggle-status" data-id="'.$users->id.'" data-status="1">Pending</button>';
                    
                }
                return '<span class="badge bg-danger">Deleted</span>';
        })
        ->addColumn('actions', function ($users) {
            // return '<a href="'.route('admin.users.show.data', $admins->id).'" class="btn btn-sm btn-primary">View</a>
            //         <a href="'.route('admin.users.edit.data', $admins->id).'" class="btn btn-sm btn-warning">Edit</a>
            //         <button class="btn btn-sm btn-danger delete-user" data-id="'.$admins->id.'">Delete</button>';
             return '<a href="'.route('owner.employee.edit.data', $users->id).'" class="btn btn-sm btn-warning">Edit</a>
                    <button class="btn btn-sm btn-danger delete-user" data-id="'.$users->id.'">Delete</button>';
            
        })
        ->rawColumns(['status', 'actions'])
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

        return redirect()->back()->with('success', 'Admin User Updated Successfully!');
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










}
