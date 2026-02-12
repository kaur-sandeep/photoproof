<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\DataTableTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;



class UserController extends Controller
{
    use DataTableTrait;
    
   public function index()
    {
        return view('admin.users.index');
    }
    
    public function list(Request $request)
    {
        return $this->getData(
            $request,
            User::class,
            ['name', 'email', 'profile_image', 'phone_number']
        );
    }

    public function create(){
        return view('admin/users/add');
    }
    
    public function store(Request $request){
        
       $request->validate([
        'name'   => 'required|string|max:255',
        'email'  => 'required|email|max:255',
        'number' => 'required|string|min:10|max:15',
        'password' => 'required|min:6',
        'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $imageName = null;

    if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('profile', 'public');
        $imageName = basename($path);
    }

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone_number' => $request->number,
        'password' => Hash::make($request->password),
        'profile_image' => $imageName,
    ]);

    return redirect()->back()->with('success', 'User added successfully!');
    }

    public function edit(Request $request,$user_id){
        $user = User::findOrFail($user_id);
        return view('admin/users/edit',compact('user'));

    }

    public function update(Request $request,$user_id){
    $user = User::findOrFail($user_id);
    $request->validate([
        'name'   => 'required|string|max:255',
        'email'  => 'required|email|max:255|unique:users,email',
        'number' => 'required|string|min:10|max:15',
        'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone_number = $request->number;

    if ($request->hasFile('profile_image')) {

        // Delete old image
        if ($user->profile_image && Storage::disk('public')->exists('profile/' . $user->profile_image)) {
            Storage::disk('public')->delete('profile/' . $user->profile_image);
        }

        // Store new image
        $path = $request->file('profile_image')->store('profile', 'public');
        $user->profile_image = basename($path);
    }

    $user->save();

    return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function updateStatus(Request $request){
        $request->validate([
        'id' => 'required|exists:users,id',
        'status' => 'required|in:-1,0,1'
    ]);

    $user = User::findOrFail($request->id);
    if ($request->status == -1) {
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
    $user->status = $request->status;
    $user->save();
    return response()->json([
        'success' => true,
        'message' => 'User status updated successfully'
    ]);
    }
}
