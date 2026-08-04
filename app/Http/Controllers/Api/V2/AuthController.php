<?php

namespace App\Http\Controllers\Api\V2;
use Illuminate\Support\Facades\Http;
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
use App\Models\PhotoUploadTrack;
use Jenssegers\Agent\Agent;
use App\Models\Setting;
use App\Models\Notifications;
use App\Models\EmployeeOtp;
use App\Models\Organization;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\OrganizationSubscriptions;
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
            // 'display_name' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:15360', // max 5MB
            'location' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

       // $user = $request->user(); // Assuming auth:sanctum
       $user = null;

       $blockedUser = User::where('device_id', $request->device_id)
            ->where('state', -1)
            ->first();

        if ($blockedUser) {
            return response()->json([
                'status' => false,
                'message' => 'This device has been restricted. Please contact your administrator.'
            ], 403);
        }


        if ($request->user()) {
            $user = $request->user();
        } else {
            
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $request->display_name,
                    'email' => $request->email,
                    'password' => bcrypt('guest123'), // temporary password
                    'plan_id' => \App\Models\Plan::where('name', 'Free')->first()->id
                ]);
            }
        }
        // $photoCount = \App\Models\PhotoDetail::where('user_id', $user->id)->count();
        $photoCount = \App\Models\PhotoDetail::where('user_id', $user->id)
        ->whereDate('created_at', Carbon::today())
        ->count();

        $plan = $user->plan;

       if ($photoCount >= $plan->photo_limit) {
            return response()->json([
                'status' => false,
                'message' => 'You have reached your photo upload limit.'
            ], 403);
        }
        // Upload photo
        //$path = $request->file('photo')->store('photos', 'public'); // storage/app/public/photos
           // thumnil code start
       // Upload original
       // Upload original image
        $path = $request->file('photo')->store('photos', 'public');

        // Thumbnail path
        $thumbnailPath = 'photos/thumbnails/' . basename($path);

        // Full directory path
        $thumbnailDir = Storage::disk('public')->path('photos/thumbnails');

        // Create directory if it doesn't exist
        if (!File::exists($thumbnailDir)) {
            File::makeDirectory($thumbnailDir, 0755, true);
        }

        // Create thumbnail
        $manager = new ImageManager(new Driver());

        $image = $manager->read(Storage::disk('public')->path($path));

        $image->scale(width: 300);

        $image->save(Storage::disk('public')->path($thumbnailPath));
       
       
       //end thumbnil
       

        // Create record
        $photo = PhotoDetail::create([
            'random_id' =>$request->id, // generate unique random id
            'user_id' => $user->id,
            'name' => $request->display_name,
            'location' => $request->location,
            'photo' => $path,
             'thumbnail' => $thumbnailPath,
            'state' => 1,
            'word_api_date_time' => $request->word_api_date_time,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'device_type' => $request->device_type,
            'device_brand' => $request->device_brand,
            'device_model' => $request->device_model,
            'device_name' => $request->device_name,
            'device_manufacturer' => $request->device_manufacturer,
            'android_version' => $request->android_version,
            'android_sdk' => $request->android_sdk,
            'ios_system_version' => $request->ios_system_version,
            'ios_identifier' => $request->ios_identifier,
             'country' => $request->country,
            'country_code' => $request->country_code,
            'region' => $request->region,
            'region_name' => $request->region_name,
            'city' => $request->city,
            'zip' => $request->zip,
            'timezone' => $request->timezone,
            'display_name_flag' => $request->display_name_flag,
            'display_location_flag' => $request->display_location_flag,
            'display_self_photo_flag' => $request->display_self_photo_flag,
            'display_qrcode_flag' => $request->display_qrcode_flag,
            'meta_data'=>json_decode($request->meta_data)
        ]);
        
        $ip = $request->ip();
        // $ip ='202.164.57.197';
        // $ip ='192.168.0.90';
        $userAgent = $request->header('User-Agent');
        $referer = $request->headers->get('referer');
        $agent = new Agent();
        $browser = $agent->browser();
        $platform = $agent->platform();
        $device = $agent->device();
        $deviceType = 'Mobile';

        $location = $this->getLocationFromIp($ip);
      
        PhotoUploadTrack::create([
            'photo_detail_id' => $photo->id,
            'user_id' => $user->id,
            'ip_address' => $ip,
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device,
            'device_type' => $deviceType,
            'user_agent' => $userAgent,
            'referer' => $referer,
            'country' => $location['country'] ?? null,
            'country_code' => $location['countryCode'] ?? null,
            'region' => $location['region'] ?? null,
            'region_name' => $location['regionName'] ?? null,
            'city' => $location['city'] ?? null,
            'zip' => $location['zip'] ?? null,
            'latitude' => $location['lat'] ?? null,
            'longitude' => $location['lon'] ?? null,
            'timezone' => $location['timezone'] ?? null,
            'isp' => $location['isp'] ?? null,
            'org' => $location['org'] ?? null,
            'as_name' => $location['as'] ?? null,
            'ip_query' => $location['query'] ?? null,
        ]);

        $data = json_encode([
        'userAgent' => $userAgent,
        'referer' => $referer,
        'browser' => $browser,
        'platform' => $platform,
        'device' => $device,
        'deviceType' => $deviceType,
        'ip' => $ip,
        'country' => $location['country'] ?? null,
        'region' => $location['regionName'] ?? null,
        'city' => $location['city'] ?? null,
        'zip' => $location['zip'] ?? null,
        'latitude' => $location['lat'] ?? null,
        'longitude' => $location['lon'] ?? null,
        'timezone' => $location['timezone'] ?? null,
        ]);
        
        // save data into notifications table //

            Notifications::create([
            'photo_random_id' => $request->id,
            'name' => $request->display_name,
            'email' => $request->email,
            'type'=>'upload photo',
            'data' => $data, 
            'is_read' => false
        ]);

        // send email to user
        // Send email only if email is enabled
        $settings = \App\Models\Setting::first();

        if ($settings && $settings->email_enabled) {
            $photoUrl = $photo->photo_url; // from accessor

          $photoUrl = $photo->photo_url; // from accessor
              $photopageurl = 'https://photoproof.cogniter.com/photo/'.$request->id;  


            $slot = '
<table width="100%" align="center" cellpadding="0" cellspacing="0" style="font-family:Arial,sans-serif;">
<tr>
<td style="font-family:Arial,sans-serif;">

<p>Hello '.$user->name.',</p>

<p>Your photo has been uploaded successfully. Please find the details below:</p>

<p><strong>Photo ID:</strong> '.$photo->random_id.'<br>
<strong>Location:</strong> '.$photo->location.'<br>
<strong>Date Time:</strong> '.$photo->word_api_date_time.'</p>

<p>Share this link with anyone for proof verification:<br>

<a href="'.$photopageurl.'" target="_blank" style="color:#1a73e8;">
'.$photopageurl.'
</a>
</p>

<hr>

<p><strong>Photo Preview:</strong></p>

<p>
    <img src="'.$photoUrl.'" width="300" style="display:block;border:0;">
</p>




</td>
</tr>
</table>
';

            // Send email to user
            $user->notify(new CommonMailNotification(
                'Photo Uploaded Successfully',
                $slot
            ));
        }
        //end

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
       
        $query = PhotoDetail::where('state', 1);

        if ($user->hasRole('owner')) {
            $query->where('organization_id', $user->organization_id);
        } else {
            $query->where('user_id', $user->id);
        }
   
               

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

    public function updateProfile(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $imagePath=null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
        }
        if (!$user) {
             $freePlan = \App\Models\Plan::where('name', 'Free')->first();
                $user = User::create([
                    'name' => $request->name ,
                    'email' => $request->email,
                    'password' => bcrypt('guest123'), // temporary password
                    'profile_image'=>$imagePath,
                    'location'=>$request->location,
                    'country'=>$request->country,
                    'city'=>$request->city,
                    'region_name'=>$request->region_name,
                    'zip'=>$request->zip,
                    'device_type'=>$request->device_type,
                    'timezone'=>$request->timezone,
                    'plan_id' => \App\Models\Plan::where('name', 'Free')->first()->id
                ]);
                   return response()->json([
                    'status' => true,
                    'message' => 'Profile added successfully.',
                    'user' => $user
                ]);
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        // If updating email itself, use a different field to identify user
        if ($request->filled('new_email')) {
            $user->email = $request->new_email;
        }

        if ($request->filled('password')) {
            $user->password = \Hash::make($request->password);
        }

        if ($request->filled('phone_number')) {
            $user->phone_number = $request->phone_number;
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                \Storage::disk('public')->delete($user->profile_image);
            }

            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $imagePath;
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    private function getLocationFromIp($ip)
    {
        if ($ip == '127.0.0.1') {
            return null;
        }

        try {

            $response = Http::get("http://ip-api.com/json/{$ip}");

            if ($response->successful()) {

                $data = $response->json();
                if ($data['status'] == 'success') {
                    return $data; // return full array
                }
            }

        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
    public function testVersion()
    {
                echo "v1 working";
    }

    
    public function requestOtp(Request $request)
    {
        // 1. Validate Email
        $request->validate([
            'email' => 'required|email',
        ]);
        // 2. Find User
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Employee not found.'
            ],404);
        }

        // 3. Check Employee Role
        if (!$user->hasAnyRole(['employee','owner'])) {
            return response()->json([
                'message'=>'Unauthorized.'
            ],403);
        }

        // 4. Check User Active
        if ($user->state != 1) {

            return response()->json([
                'message'=>'User account inactive.'
            ],403);
        }
    

        // 5. Check Organization Active
        $organization = Organization::find($user->organization_id);
        if (!$organization || $organization->state != 1) {
            return response()->json([
                'message'=>'Organization inactive.'
            ],403);

        }

        // 6. Check Active Subscription

        $subscription = OrganizationSubscriptions::where(
                'organization_id',
                $organization->id
            )
            ->where('state',1)
            ->where('starts_at','<=',now())
            ->where('expires_at','>=',now())
            ->first();

        if (!$subscription){

            return response()->json([
                'message'=>'Organization subscription expired.'
            ],403);

        }

        // 7. Generate OTP
        $otp = random_int(100000, 999999);
        EmployeeOtp::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->delete();

        EmployeeOtp::create([
            'user_id'    => $user->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);
        // 8. Save OTP

        // 9. Send Email
        $slot = "
            <p>Hello {$user->name},</p>

            <p>Your One-Time Password (OTP) for PhotoProof organization login is:</p>

            <h2 style='font-size:30px;color:#2563eb;text-align:center;'>{$otp}</h2>

            <p>This OTP is valid for <strong>10 minutes</strong>.</p>

            <p>If you did not request this OTP, please ignore this email.</p>

            ";

   
        try {

                $user->notify(new CommonMailNotification(
                    'Your PhotoProof Login OTP',
                    $slot
                ));

                return response()->json([
                    'status' => true,
                    'message' => 'OTP has been sent to your registered email address.',
                    'otp'=>$otp
                ]);

            } catch (\Exception $e) {

                \Log::error('OTP Email Error', [
                    'email' => $user->email,
                    'error' => $e->getMessage()
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Unable to send OTP email.',
                    'error' => $e->getMessage() // remove in production
                ], 500);
            }
       
    
    }




        public function verifyOtp(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'otp'   => 'required|digits:6',
                'device_id'=>'required',
            ]);

            // Find user
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.'
                ], 404);
            }

            $blockedUser = User::where('device_id', $request->device_id)
            ->where('state', -1)
            ->first();

            if ($blockedUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'This device has been restricted. Please contact your administrator.'
                ], 403);
            }

            // Check user belongs to an organization
            if (!$user->organization_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'This user is not associated with any organization.'
                ], 422);
            }

            // Get latest OTP
            $EmployeeOtp = EmployeeOtp::where('user_id', $user->id)
                ->latest()
                ->first();

            if (!$EmployeeOtp) {
                return response()->json([
                    'status' => false,
                    'message' => 'OTP not found.'
                ], 422);
            }

            // Already verified
            if ($EmployeeOtp->verified_at) {
                return response()->json([
                    'status' => false,
                    'message' => 'OTP has already been used.'
                ], 422);
            }

            // OTP expired
            if (now()->greaterThan($EmployeeOtp->expires_at)) {
                return response()->json([
                    'status' => false,
                    'message' => 'OTP has expired.'
                ], 422);
            }

            // Verify OTP
            if ($EmployeeOtp->otp != $request->otp) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP.'
                ], 422);
            }

            // Get organization
            $organization = Organization::find($user->organization_id);

            if (!$organization || $organization->state != 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Organization is inactive.'
                ], 422);
            }

            // Get active subscription
            // $subscription = OrganizationSubscriptions::where(
            //     'organization_id',
            //     $organization->id
            //     )
            //     ->where('state',1)
            //     ->where('starts_at','<=',now())
            //     ->where('expires_at','>=',now())
            //     ->first();

            $subscription = OrganizationSubscriptions::with('plan')
            ->where('organization_id', $organization->id)
            ->where('state', 1)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->first();
         
            if (!$subscription){

                return response()->json([
                    'message'=>'Organization subscription expired.'
                ],403);

            }
            
            $EmployeeOtp->update([
                'verified_at' => now(),
            ]);
            // Save device ID
            $user->update([
                'device_id' => $request->device_id,
            ]);

            // Remove old login tokens
            $user->tokens()->delete();

            // Create new login token
            $token = $user->createToken('organization-app')->plainTextToken;


            // Create new Sanctum token
            $token = $user->createToken('organization-app')->plainTextToken;
            
            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully.',
                 'token' => $token,

                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'user_types' => $user->getRoleNames(),
                     'profile_image' => $user->profile_image
                                    ? asset('storage/' . $user->profile_image)
                                    : null,
                ],

                'organization' => [
                    'id' => $organization->id,
                    'organization_name ' => $organization->organization_name ,
                    'organization_code' => $organization->organization_code,
                ],

                'subscription' => [
                    'plan_name' => optional($subscription->plan)->name,
                    'photo_limit' => $subscription->monthly_photo_limit,
                    'photo_used' => $subscription->monthly_photo_used,
                    'expires_at' => $subscription->expires_at,
                ]
            ]);
        }

    public function employeeUploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'display_name' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:15360', // max 5MB
            'location' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user(); // Assuming auth:sanctum
        if ($user->state != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Employee account is inactive.'
            ], 403);
        }
     
       if (!$user->hasAnyRole(['employee','owner'])) {
            return response()->json([
                'message'=>'Unauthorized.'
            ],403);
        }

        $organization = Organization::find($user->organization_id);

        if (!$organization || $organization->state != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Organization is inactive.'
            ], 403);
        }

        $subscription = OrganizationSubscriptions::where(
                'organization_id',
                $organization->id
            )
            ->where('state',1)
            ->where('starts_at','<=',now())
            ->where('expires_at','>=',now())
            ->first();

        if (!$subscription){

            return response()->json([
                'message'=>'Organization subscription expired.'
            ],403);

        }

        if ($subscription->monthly_photo_used >= $subscription-> monthly_photo_limit ) {

            return response()->json([
                'status' => false,
                'message' => 'Monthly organization photo limit reached.'
            ], 403);

        }

        // Upload photo
       
       //$path = $request->file('photo')->store('photos', 'public'); // storage/app/public/photos   //commant for thumbnil code
       // thumnil code start
       // Upload original
       // Upload original image
        $path = $request->file('photo')->store('photos', 'public');

        // Thumbnail path
        $thumbnailPath = 'photos/thumbnails/' . basename($path);

        // Full directory path
        $thumbnailDir = Storage::disk('public')->path('photos/thumbnails');

        // Create directory if it doesn't exist
        if (!File::exists($thumbnailDir)) {
            File::makeDirectory($thumbnailDir, 0755, true);
        }

        // Create thumbnail
        $manager = new ImageManager(new Driver());

        $image = $manager->read(Storage::disk('public')->path($path));

        $image->scale(width: 300);

        $image->save(Storage::disk('public')->path($thumbnailPath));
       
       
       //end thumbnil
       
       
       
        // Create record
        $photo = PhotoDetail::create([
            //'random_id' => Str::uuid(), // generate unique random id
            'random_id' =>$request->id, // generate unique random id
            'user_id' => $user->id,
            'organization_id' => $user->organization_id,
            'name' =>  $user->name,
            'location' => $request->location,
            'photo' => $path,
            'thumbnail' => $thumbnailPath,
            'state' => 1,
            'word_api_date_time' => $request->word_api_date_time,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'device_type' => $request->device_type,
            'device_brand' => $request->device_brand,
            'device_model' => $request->device_model,
            'device_name' => $request->device_name,
            'device_manufacturer' => $request->device_manufacturer,
            'android_version' => $request->android_version,
            'android_sdk' => $request->android_sdk,
            'ios_system_version' => $request->ios_system_version,
            'ios_identifier' => $request->ios_identifier,
             'country' => $request->country,
            'country_code' => $request->country_code,
            'region' => $request->region,
            'region_name' => $request->region_name,
            'city' => $request->city,
            'zip' => $request->zip,
            'timezone' => $request->timezone,
            'display_name_flag' => $request->display_name_flag,
            'display_location_flag' => $request->display_location_flag,
            'display_self_photo_flag' => $request->display_self_photo_flag,
            'display_qrcode_flag' => $request->display_qrcode_flag,
            'meta_data'=>json_decode($request->meta_data)
            
        ]);
        $subscription->increment('monthly_photo_used', 1);
        $ip = $request->ip();
        // $ip ='202.164.57.197';
        // $ip ='192.168.0.90';
        $userAgent = $request->header('User-Agent');
        $referer = $request->headers->get('referer');
        $agent = new Agent();
        $browser = $agent->browser();
        $platform = $agent->platform();
        $device = $agent->device();
        $deviceType = 'Mobile';

        $location = $this->getLocationFromIp($ip);
      
        PhotoUploadTrack::create([
            'photo_detail_id' => $photo->id,
            'user_id' => $user->id,
            'ip_address' => $ip,
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device,
            'device_type' => $deviceType,
            'user_agent' => $userAgent,
            'referer' => $referer,
            'country' => $location['country'] ?? null,
            'country_code' => $location['countryCode'] ?? null,
            'region' => $location['region'] ?? null,
            'region_name' => $location['regionName'] ?? null,
            'city' => $location['city'] ?? null,
            'zip' => $location['zip'] ?? null,
            'latitude' => $location['lat'] ?? null,
            'longitude' => $location['lon'] ?? null,
            'timezone' => $location['timezone'] ?? null,
            'isp' => $location['isp'] ?? null,
            'org' => $location['org'] ?? null,
            'as_name' => $location['as'] ?? null,
            'ip_query' => $location['query'] ?? null,
        ]);

        $data = json_encode([
        'userAgent' => $userAgent,
        'referer' => $referer,
        'browser' => $browser,
        'platform' => $platform,
        'device' => $device,
        'deviceType' => $deviceType,
        'ip' => $ip,
        'country' => $location['country'] ?? null,
        'region' => $location['regionName'] ?? null,
        'city' => $location['city'] ?? null,
        'zip' => $location['zip'] ?? null,
        'latitude' => $location['lat'] ?? null,
        'longitude' => $location['lon'] ?? null,
        'timezone' => $location['timezone'] ?? null,
        ]);
        
        // save data into notifications table //

        Notifications::create([
            'photo_random_id' => $user->id,
            'organization_id' => $user->organization_id,
            'name' => $user->name,
            'email' => $user->email,
            'type'=>'upload photo',
            'data' => $data, 
            'is_read' => false
        ]);

        // send email to user
        // Send email only if email is enabled
        $organization = Organization::find($user->organization_id);
        $owner = User::where('organization_id', $user->organization_id)
        ->role('owner')
        ->where('state', 1)
        ->first();

        if ($organization && $organization->enable_photo_email) {
          $photoUrl = $photo->photo_url; // from accessor
              $photopageurl = 'https://photoproof.cogniter.com/photo/'.$request->id;  


            $slot = '
                <table width="100%" align="center" cellpadding="0" cellspacing="0" style="font-family:Arial,sans-serif;">
                <tr>
                <td style="font-family:Arial,sans-serif;">

                <p>Hello '.$owner->name.',</p>

                <p>Your employee has uploaded a new photo. Please find the details below:</p>
                  <strong>Employee Name:</strong> '.$user->name.'<br>
                <strong>Employee Email:</strong> '.$user->email.'<br>
                <p><strong>Photo ID:</strong> '.$photo->random_id.'<br>
                <strong>Location:</strong> '.$photo->location.'<br>
                <strong>Date Time:</strong> '.$photo->word_api_date_time.'</p>

                <p>Share this link with anyone for proof verification:<br>

                <a href="'.$photopageurl.'" target="_blank" style="color:#1a73e8;">
                '.$photopageurl.'
                </a>
                </p>

                <hr>

                <p><strong>Photo Preview:</strong></p>

                <p>
                    <img src="'.$photoUrl.'" width="300" style="display:block;border:0;">
                </p>




                </td>
                </tr>
                </table>
                ';

            // Send email to user
            $owner->notify(new CommonMailNotification(
                'Employee Photo Uploaded',
                $slot
            ));
        }
        //end
           $subscription = OrganizationSubscriptions::with('plan')
            ->where('organization_id', $organization->id)
            ->where('state', 1)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->first();
        return response()->json([
            'status' => true,
            'message' => 'Photo uploaded successfully',
            'photo' => $photo,
           'subscription' => [
                    'plan_name' => optional($subscription->plan)->name,
                    'photo_limit' => $subscription->monthly_photo_limit,
                    'photo_used' => $subscription->monthly_photo_used,
                    'expires_at' => $subscription->expires_at,
                ]
        ]);
    }

    public function getCities(Request $request)
    {
        $user = $request->user();

        $query = PhotoDetail::query();

        if ($user->hasRole('owner')) {
            $query->where('organization_id', $user->organization_id);
        } else {
            $query->where('user_id', $user->id);
        }

        $cities = $query->whereNotNull('city')
            ->where('city', '!=', '')
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return response()->json([
            'status' => true,
            'cities' => $cities,
        ]);
    }

       public function deleteAccount(Request $request)
        {
            $user = $request->user();
            $user->update([
                'state' => -1,
                'device_id' => null,
            ]);

            // Revoke all login tokens
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Account deleted successfully.'
            ]);
        }

}
