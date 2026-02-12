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

        // Generate token
        $token = $this->generateResetToken($email);

        // Build reset URL
        $resetUrl = url("/password/reset/{$token}?email={$email}");

        // Send mail
        Mail::send('admin.emails.passwordreset', ['url' => $resetUrl], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Password Reset Link');
        });


        // If mail sent successfully
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
        // Generate a random reset token
        $token = Str::random(60);

        // Insert the token into the password_resets table
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ]);

        return $token;
    }
    
    
    
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
