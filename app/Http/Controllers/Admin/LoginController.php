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
        ],[
            'email.required' => 'Email is required.',
            'password.required' => 'Password is required.',
        ]);

        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid Credentials');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function profile()
    {
        return view('admin.profile');
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

        // 🔥 Expire after 1 hour
        if ($record->created_at < now()->subHour()) {
        // if ($record->created_at < now()->subMinutes(2)) {

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
        if($request->password == $request->confirm_password){
            
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
    
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
