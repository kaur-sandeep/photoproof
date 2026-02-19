<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }


   
    public function list(Request $request){

    $admins = Admin::where('state', '!=', -1)->get();
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
        ->addColumn('created_at', function ($admins) {
            return $admins->created_at ?? '--'; // if device is null, show --
        })
        ->addColumn('phone_number', function ($admins) {
            return $admins->phone_number ?? '--'; // if device is null, show --
        })
        ->addColumn('status', function ($admins) {
                if ($admins->state == 1) {
                    return '<button class="btn btn-sm btn-warning toggle-status" data-id="'.$admins->id.'" data-status="0">Set Inactive</button>';
                }
                if ($admins->state == 0) {
                    return '<button class="btn btn-sm btn-success toggle-status" data-id="'.$admins->id.'" data-status="1">Set Active</button>';
                    
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
        'number' => 'required|numeric|digits_between:10,14',
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
            'phone_number' => $request->number,
            'password' => Hash::make($request->password),
            'profile_image' => $imageName,
        ]);

        return redirect()->back()->with('success', 'User added successfully!');

    }
    

    public function updateStatus(Request $request){
        $id = $request->input('id');
        $status = $request->input('status');
        $request->validate([
            'id' => 'required|exists:admins,id',
            'status' => 'required|in:-1,0,1'
        ]);

        $status = $request->status;
        $admin = Admin::findOrFail($id);
        $admin->state = $status;
        $admin->save();
        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully'
        ]);
    }

    public function editUsers(Request $request,$user_id){
        $admin = Admin::findOrFail($user_id);
        return view('admin/edit',compact('admin'));
    }

     public function updateUsers(Request $request,$user_id){
        $admin = Admin::findOrFail($user_id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'number' => 'required|numeric|digits_between:10,14',
            'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $admin->name = $request->name;
        // $user->emarenderRecordCountil = $request->email;
        $admin->phone_number = $request->number;

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
        }else if(Hash::check($request->old_password, $admin->password)) {
            return back()->withErrors([
                'new_password' => 'New Password is not equal to he old password'
            ]);
        }
        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return back()->with('success', 'Password changed successfully');
    }





    
}
