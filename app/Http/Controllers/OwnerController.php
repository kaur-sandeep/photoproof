<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Helpers\ActivityLogger;
use Yajra\DataTables\DataTables;
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





}
