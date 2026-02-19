<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Services\EmailService;
use App\Notifications\CommonMailNotification;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\PhotoDetail;


class LoginController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email is required.',
            'password.required' => 'Password is required.',
        ]);

        // ✅ Step 1: Check if email exists
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withInput()->with('error', 'The email address does not exist.');
        }

        // ✅ Step 2: Check password
        if (!Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            return back()->withInput()->with('error', 'Invalid Credentials');
        }

        // ✅ Step 3: Login success
        return redirect()->route('admin.dashboard');
    }


    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPhotos = PhotoDetail::count();
        $totalViews = PhotoDetail::sum('view_count');
        return view('admin.dashboard',compact('totalUsers','totalPhotos','totalViews'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }
    
    public function forgotPassword()
    {
        return view('admin.forgot-password');
    }


    public function sendPasswordRestLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        try {
            $email = $request->email;
            $token = $this->generateResetToken($email);
            $resetUrl = url("/password/reset/{$token}?email={$email}");
            // Mail::send('admin.emails.passwordreset', ['url' => $resetUrl], function ($message) use ($email) {
            //     $message->to($email)
            //             ->subject('Password Reset Link');
            // });
            $admin = Admin::where('email', $request->email)->first();
            $slot = '
            <p>Hello '.$email.',</p>
            <p>Please click the button below to Reset your Password:</p>
            <p>
                <a href="'.$resetUrl.'" class="button">Reset Password</a>
            </p>
            <p>If button does not work, copy this link:</p>
            <p>'.$resetUrl.'</p>
        ';

        $admin->notify(new CommonMailNotification(
            'Reset Your Password',
            $slot
        ));
        return redirect()->route('admin.login')
            ->with('success', 'Password reset link sent successfully to your email.');

        } catch (\Exception $e) {

            Log::error('Password reset mail failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong! Unable to send reset link.');
        }
    }
    
    public function generateResetToken($email)
    {
        $token = Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ]);

        return $token;
    }

    public function showResetForm(Request $request, $token)
    {
   
        if (!$request->has('email')) {
            return redirect()->route('admin.login')
                ->with('error', 'Invalid password reset link.');
        }
        
        $record = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->where('token', $token)
        ->first();

        if (!$record) {
            return redirect()->route('admin.password.expired');
        }

        // Expire after 1 hour
        if ($record->created_at < now()->subHour()) {

            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return redirect()->route('admin.password.expired');
        }

        return view('admin.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withErrors(['email' => 'Admin not found.']);
        }
     
        $admin->password = Hash::make($request->password);
        $admin->save();
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('admin.login')
            ->with('success', 'Password reset successfully.');
    }

    public function expireLink()
    {
        return view('admin.link-expired');
    }
    
    public function profileUpdate(Request $request)
    {
        
        $admin = Auth::guard('admin')->user();

        // Validation
        $request->validate([
            'name'   => 'required|string|max:255',
            'number' => 'required|string|min:10|max:15',
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update fields
        $admin->name = $request->name;
        $admin->phone_number = $request->number;

        // If email is readonly, no need to update
        if ($request->filled('email')) {
            $admin->email = $request->email;
        }
        // Handle image upload
        if ($request->hasFile('image')) {

        // Delete old image
        if ($admin->profile_image && Storage::disk('public')->exists('profile/' . $admin->profile_image)) {
            Storage::disk('public')->delete('profile/' . $admin->profile_image);
        }

        // Store image (auto generate name)
        $path = $request->file('image')->store('profile', 'public');

        // Save only filename
        $admin->profile_image = basename($path);
    }

        $admin->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
        
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
    public function showUserResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            abort(404);
        }

        return view('user.reset-password', compact('token', 'email'));
    }
    public function resetPasswordUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $record = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !\Hash::check($request->token, $record->token)) {
            return redirect('/link/expired');
        }

        $user = \App\Models\User::where('email', $request->email)->first();
        $user->password = \Hash::make($request->password);
        $user->save();

        \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

         return back()->with('success', 'Password reset successfully. You can now login from your app.');
    }
}
