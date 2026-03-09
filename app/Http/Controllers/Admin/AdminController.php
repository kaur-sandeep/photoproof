<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Helpers\ActivityLogger;
use App\Helpers\DateTime;
class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }
    public function list(Request $request){

    $admins = Admin::where('state', '!=', -1)->orderBy('created_at', 'desc')->get();
    return DataTables::of($admins)
        ->addIndexColumn()
        ->addColumn('profile_image', function ($admins) {
                $default = "https://cdn-icons-png.flaticon.com/512/149/149071.png";

                return '<img src="'
                    . ($admins->profile_image 
                        ? asset('storage/profile/' . $admins->profile_image) 
                        : $default)
                    . '" width="40" height="40" class="rounded-circle">';
            })
        ->addColumn('last_login_at', function ($admins) {
            return DateTime::dateFormat($admins->last_login_at) ?? '--'; // if device is null, show --
        })
        ->addColumn('created_at', function ($admins) {
            return DateTime::dateFormat($admins->created_at) ?? '--'; // if device is null, show --
        })
        ->addColumn('phone_number', function ($admins) {
            return $admins->phone_number ?? '--'; // if device is null, show --
        })
        ->addColumn('status', function ($admins) {
                if ($admins->state == 1) {
                    return '<button class="btn btn-sm btn-success toggle-status" data-id="'.$admins->id.'" data-status="0">Active</button>';
                }
                if ($admins->state == 0) {
                    return '<button class="btn btn-sm btn-warning toggle-status" data-id="'.$admins->id.'" data-status="1">Inactive</button>';
                    
                }
                return '<span class="badge bg-danger">Deleted</span>';
        })
        ->addColumn('actions', function ($admins) {
            // return '<a href="'.route('admin.users.show.data', $admins->id).'" class="btn btn-sm btn-primary">View</a>
            //         <a href="'.route('admin.users.edit.data', $admins->id).'" class="btn btn-sm btn-warning">Edit</a>
            //         <button class="btn btn-sm btn-danger delete-user" data-id="'.$admins->id.'">Delete</button>';
             return '<a href="'.route('admin.users.edit.data', $admins->id).'" class="btn btn-sm btn-warning">Edit</a>
                    <button class="btn btn-sm btn-danger delete-user" data-id="'.$admins->id.'">Delete</button>';
            
        })
        ->rawColumns(['profile_image', 'status', 'actions'])
        ->make(true);
        
    }

    public function create(){
        return view('admin.add');
    }

    public function addUser(Request $request){
       $request->validate([
        'name'   => 'required|string|max:255',
        'email'  => 'required|email|unique:users,email',
        'phone_number' => 'required|numeric|digits_between:10,14',
        'password' => 'required|min:6',
        'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $imageName = null;
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile', 'public');
            $imageName = basename($path);
        }
        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'profile_image' => $imageName,
        ]);
        ActivityLogger::log(
            'Create',
            'Admin Users',
            'Created new admin: ' . $request->email
        );
        return redirect()->back()->with('success', 'User added successfully!');

    }
    

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required|in:-1,0,1'
        ]);

        $admin = Admin::findOrFail($request->id);

        // ✅ Get old status before update
        $oldStatus = $admin->state;

        // ✅ Update status
        $admin->state = $request->status;
        $admin->save();

        // ✅ Convert status to readable text
        $statusText = [
            -1 => 'Deleted',
            0  => 'Inactive',
            1  => 'Active',
        ];

        // ✅ Activity Log
        ActivityLogger::log(
            'Update',
            'Admin Users',
            'Changed status of ' . $admin->email .
            ' from ' . ($statusText[$oldStatus] ?? $oldStatus) .
            ' to ' . ($statusText[$request->status] ?? $request->status)
        );

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully'
        ]);
    }
    public function editUsers(Request $request,$user_id){
        $admin = Admin::findOrFail($user_id);
        return view('admin/edit',compact('admin'));
    }

    //  public function updateUsers(Request $request,$user_id){
    //     $admin = Admin::findOrFail($user_id);
    //     $request->validate([
    //         'name'   => 'required|string|max:255',
    //         'number' => 'required|numeric|digits_between:10,14',
    //         'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     $admin->name = $request->name;
    //     // $user->emarenderRecordCountil = $request->email;
    //     $admin->phone_number = $request->number;

    //     if ($request->hasFile('profile_image')) {

    //         // Delete old image
    //         if ($admin->profile_image && Storage::disk('public')->exists('profile/' . $admin->profile_image)) {
    //             Storage::disk('public')->delete('profile/' . $admin->profile_image);
    //         }

    //         // Store new image
    //         $path = $request->file('profile_image')->store('profile', 'public');
    //         $admin->profile_image = basename($path);
    //     }

    //     $admin->save();
    //     $oldData = $admin->only(['name', 'phone_number', 'profile_image']);
    //     $admin->update($request->only(['name', 'phone_number', 'profile_image']));
    //     foreach (['name', 'phone_number', 'profile_image'] as $field) {
    //         if (($oldData[$field] ?? null) !== ($admin->$field ?? null)) {
    //             $old = $oldData[$field] ?? '-';
    //             $new = $admin->$field ?? '-';
    //             $changes[] = ucfirst(str_replace('_', ' ', $field)) . " changed from '$old' to '$new'";
    //         }
    //     }
    //    if (!empty($changes)) {
    //         $description = implode('; ', $changes);
            
    //         ActivityLogger::log(
    //             'Update',
    //             'Admin Users',
    //             $description
    //         );
    //     }

    //     return redirect()->back()->with('success', 'User updated successfully!');
    // }

    public function updateUsers(Request $request, $user_id)
    {
        $admin = Admin::findOrFail($user_id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone_number'        => 'required|numeric|digits_between:10,14',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Capture old data BEFORE updating
        $oldData = $admin->only(['name', 'phone_number', 'profile_image']);

        // Update fields
        $admin->name = $request->name;
        $admin->phone_number = $request->phone_number;

        if ($request->hasFile('profile_image')) {

            // Delete old image
            if ($admin->profile_image && Storage::disk('public')->exists('profile/' . $admin->profile_image)) {
                Storage::disk('public')->delete('profile/' . $admin->profile_image);
            }

            // Store new image
            $path = $request->file('profile_image')->store('profile', 'public');
            $admin->profile_image = basename($path);
        }

        $admin->save();

        // Prepare changes for logging
        $changes = [];
        foreach (['name', 'phone_number', 'profile_image'] as $field) {
            if (($oldData[$field] ?? null) !== ($admin->$field ?? null)) {
                $old = $oldData[$field] ?? '-';
                $new = $admin->$field ?? '-';
                $changes[] = ucfirst(str_replace('_', ' ', $field)) . " changed from '$old' to '$new'";
            }
        }

        if (!empty($changes)) {
            $description = implode('; ', $changes);
            
            ActivityLogger::log(
                'Update',
                'Admin Users',
                $description
            );
        }

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function changePassword(){
       
        return view('admin.change-password');
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

    public function settings(){
         $settings = Setting::first();
        return view('admin.settings',compact('settings'));
    }

     public function updateSettings(Request $request)
    {
        $request->validate([
            'email_enabled'=>'required|boolean',
            'smtp_enabled' => 'required|boolean',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|numeric',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|string|in:tls,ssl,',
            'delete_photos_after_days' => 'required|numeric|min:1',
        ]);

        $settings = Setting::first();
        if (!$settings) {
            $settings = new Setting();
        }

        $settings->email_enabled = $request->email_enabled;
        $settings->smtp_enabled = $request->smtp_enabled;
        $settings->smtp_host = $request->smtp_host;
        $settings->smtp_port = $request->smtp_port;
        $settings->smtp_username = $request->smtp_username;
        $settings->smtp_password = $request->smtp_password;
        $settings->smtp_encryption = $request->smtp_encryption;
        $settings->delete_photos_after_days = $request->delete_photos_after_days;
        $settings->save();

        return back()->with('success', 'Settings updated successfully');
    }
}
    

