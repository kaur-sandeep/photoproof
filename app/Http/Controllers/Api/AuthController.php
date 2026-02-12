<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Models\PhotoDetail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Register API
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        $freePlan = \App\Models\Plan::where('name', 'Free')->first();
     
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plan_id' => $freePlan->id
        ]);

        // 👇 Send verification email
        event(new Registered($user));

        return response()->json([
            'status' => true,
            'message' => 'User Registered Successfully. Please verify your email.',
            'user' => $user
        ]);
    }


    // Login API
  public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        // Check credentials
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Credentials'
            ], 401);
        }

        // ✅ Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'status' => false,
                'message' => 'Please verify your email before logging in'
            ], 403);
        }

        // Generate token
        $token = $user->createToken('UserToken')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login Successful',
            'token' => $token,
            'user' => $user
        ]);
    }


    // Logout API
   

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function verifyEmail($id, $hash)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Check if hash matches
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid verification link'
            ], 403);
        }

        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => true,
                'message' => 'Email already verified'
            ]);
        }

        // Mark email as verified
        $user->markEmailAsVerified();

        return response()->json([
            'status' => true,
            'message' => 'Email verified successfully'
        ]);
    }
    public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:5120', // max 5MB
            'location' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user(); // Assuming auth:sanctum
        $photoCount = \App\Models\PhotoDetail::where('user_id', $user->id)->count();
        $plan = $user->plan;

        if ($photoCount >= $plan->photo_limit) {
            return response()->json([
                'status' => false,
                'message' => 'You have reached your photo upload limit.'
            ], 403);
        }
        // Upload photo
        $path = $request->file('photo')->store('photos', 'public'); // storage/app/public/photos

        // Create record
        $photo = PhotoDetail::create([
            'random_id' => Str::uuid(), // generate unique random id
            'user_id' => $user->id,
            'name' => $request->name,
            'location' => $request->location,
            'photo' => $path,
            'state' => 1
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Photo uploaded successfully',
            'photo' => $photo
        ]);
    }
}
