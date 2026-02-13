<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Models\PhotoDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Notifications\CommonMailNotification;
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
        //event(new Registered($user));
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email)
            ]
        );

        $slot = '
            <p>Hello '.$user->name.',</p>
            <p>Please click the button below to verify your email address:</p>
            <p>
                <a href="'.$verificationUrl.'" class="button">Verify Email</a>
            </p>
            <p>If button does not work, copy this link:</p>
            <p>'.$verificationUrl.'</p>
        ';

        $user->notify(new CommonMailNotification(
            'Verify Your Email Address',
            $slot
        ));

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
 public function getPhotos(Request $request)
{
    $search = $request->search;
    $user   = $request->user();

    /*
    |--------------------------------------------------------------------------
    | 1️⃣ Exact random_id match (Public Access)
    |--------------------------------------------------------------------------
    */
    if (!empty($search)) {

        $exactPhoto = PhotoDetail::where('random_id', $search)
                        ->where('state', 1)
                        ->first();

        if ($exactPhoto) {
            return response()->json([
                'status' => true,
                'photo' => $exactPhoto
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 2️⃣ Logged-in user data with LIKE search + filters
    |--------------------------------------------------------------------------
    */

    $query = PhotoDetail::where('state', 1)
                ->where('user_id', $user->id);

    /*
    |--------------------------------------------------------------------------
    | 🔎 LIKE Search (ONLY inside logged-in user data)
    |--------------------------------------------------------------------------
    */
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('random_id', 'LIKE', "%$search%")
              ->orWhere('name', 'LIKE', "%$search%")
              ->orWhere('location', 'LIKE', "%$search%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | 🔎 Additional Filters
    |--------------------------------------------------------------------------
    */
    if ($request->filled('name')) {
        $query->where('name', 'LIKE', "%" . $request->name . "%");
    }

    if ($request->filled('location')) {
        $query->where('location', 'LIKE', "%" . $request->location . "%");
    }

    /*
    |--------------------------------------------------------------------------
    | 📄 Pagination (10 per page)
    |--------------------------------------------------------------------------
    */
    $photos = $query->latest()->paginate(10);

    return response()->json([
        'status' => true,
        'photos' => $photos
    ]);
}

public function forgotPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors()->first()
        ], 422);
    }

    $user = User::where('email', $request->email)->first();

    // Generate token
    $token = Str::random(60);

    // Store token in password_reset_tokens table
    \DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        [
            'token' => bcrypt($token),
            'created_at' => now()
        ]
    );

    // Create reset link (frontend URL)
    $resetUrl = url('/reset-password?token='.$token.'&email='.$user->email);

    $slot = '
        <p>Hello '.$user->name.',</p>
        <p>Click below button to reset your password:</p>
        <p>
            <a href="'.$resetUrl.'" class="button">Reset Password</a>
        </p>
        <p>If button does not work, copy this link:</p>
        <p>'.$resetUrl.'</p>
    ';

    $user->notify(new CommonMailNotification(
        'Reset Your Password',
        $slot
    ));

    return response()->json([
        'status' => true,
        'message' => 'Password reset link sent to your email'
    ]);
}





}
