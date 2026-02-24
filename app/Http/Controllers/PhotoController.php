<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PhotoDetail;
use App\Models\PhotoView;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;
use Carbon\Carbon;
use App\Models\PhotoReport;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommonMailNotification;
class PhotoController extends Controller
{
   public function searchForm()
    {
        return view('user.search');
    }

    public function search(Request $request)
    {
        $request->validate([
            'random_id' => 'required'
        ]);

        return redirect()->route('photo.show', $request->random_id);
    }
    
    public function show(Request $request, $random_id)
    {
        // $photo = PhotoDetail::with('user')
        //             ->where('random_id', $random_id)
        //             ->first();
        $photo = PhotoDetail::with(['user', 'uploadTrack'])
            ->where('random_id', $random_id)
            ->where('state', 1)
            ->first();
     
        if (!$photo) {
            return redirect()->route('photo.search.form')
        ->with('error', 'No record found')
        ->withInput(['random_id' => $random_id]);
        }

       // $ip = $request->ip();
        $ip ='202.164.57.197';
        $userAgent = $request->header('User-Agent');
        $referer = $request->headers->get('referer');

        $agent = new Agent();

        $browser = $agent->browser();
        $platform = $agent->platform();
        $device = $agent->device();
        $deviceType = $agent->isMobile() ? 'Mobile' : 'Desktop';
        $location = $this->getLocationFromIp($ip);
        // Prevent multiple refresh count in one day
        // $alreadyViewed = PhotoView::where('photo_detail_id', $photo->id)
        //     ->where('ip_address', $ip)
        //     ->whereDate('created_at', today())
        //     ->exists();
        $alreadyViewed = PhotoView::where('photo_detail_id', $photo->id)
        ->where('ip_address', $ip)
        ->where('browser', $browser)
        ->where('platform', $platform)
        ->where('device', $device)
        ->whereDate('created_at', today())
        ->exists();

        if (!$alreadyViewed) {

            $photo->increment('view_count');

            PhotoView::create([
                    'photo_detail_id' => $photo->id,
                    'ip_address' => $ip,
                    'browser' => $browser,
                    'platform' => $platform,
                    'device' => $device,
                    'device_type' => $deviceType,
                    'referer' => $referer,
                    'user_agent' => $userAgent,
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
        }
    // Fetch setting
    $setting = Setting::first();
    $totalDays = (int) ($setting->delete_photos_after_days ?? 0); // cast to integer

    // Make sure $photo->created_at is Carbon
    $created = $photo->created_at instanceof Carbon ? $photo->created_at : Carbon::parse($photo->created_at);

    // Use diffInDays() — integer only
    $daysElapsed = $created->diffInDays(Carbon::now()); // returns integer

    //$daysAvailable = max($totalDays - $daysElapsed, 0); 
    $daysAvailable = (int) round($totalDays - $daysElapsed);
        return view('user.photo-view', compact('photo', 'daysAvailable'));
    }
   private function getLocationFromIp($ip)
    {
        if ($ip == '127.0.0.1' || str_starts_with($ip, '192.168')) {
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
    public function privacy_policy()
    {
        return view('user.privacy-policy');
    }

    public function terms_conditions()
    {
        return view('user.terms-conditions');
    }
    public function thank_you()
    {
        return view('user.thank-you');
    }
    // public function report($random_id)
    // {
    //     $photo = PhotoDetail::where('random_id', $random_id)->first();
    //     if (!$photo) {
    //         return redirect()->back()->with('error', 'Photo not found');
    //     }
        
    //     return view('user.report-photo', compact('photo'));
    // }

        public function report($random_id)
    {
            $setting = Setting::first();
            $totalDays = (int) ($setting->delete_photos_after_days ?? 0); // cast to integer

            // Make sure $photo->created_at is Carbon
            
        $photo = PhotoDetail::where('random_id', $random_id)->first();
        $created = $photo->created_at instanceof Carbon ? $photo->created_at : Carbon::parse($photo->created_at);

            // Use diffInDays() — integer only
            $daysElapsed = $created->diffInDays(Carbon::now()); // returns integer

            //$daysAvailable = max($totalDays - $daysElapsed, 0); 
            $daysAvailable = (int) round($totalDays - $daysElapsed);
        if (!$photo) {
            return redirect()->back()->with('error', 'Photo not found');
        }
        return view('user.report-this-photo', compact('photo','daysAvailable'));
    }
    public function report_submit(Request $request, $random_id){
       $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'message' => 'required',
        'g-recaptcha-response' => 'required'
    ]);

    // If captcha is enabled, then keep this
    $response = Http::asForm()->post(
        'https://www.google.com/recaptcha/api/siteverify',
        [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]
    );

    if (!$response->json()['success']) {
        return back()->with('error', 'Captcha verification failed.');
    }
    $ip = $request->ip(); // real user IP
    $ip ='202.164.57.197';
    $userAgent = $request->header('User-Agent');
    $referer = $request->headers->get('referer');

    $agent = new Agent();
    $browser = $agent->browser();
    $platform = $agent->platform();
    $device = $agent->device();
    $deviceType = $agent->isMobile() ? 'Mobile' : 'Desktop';

    // Get location from IP (if you already have this function)
    $location = $this->getLocationFromIp($ip);
    PhotoReport::create([
        'photo_random_id' => $random_id,
        'name' => $request->name,
        'email' => $request->email,
        'message' => $request->message,
        'is_read' => 0,
        'ip_address' => $ip,
        'browser' => $browser,
        'platform' => $platform,
        'device' => $device,
        'device_type' => $deviceType,
        'user_agent' => $userAgent,
        'referer' => $referer,
        'country' => $location['country'] ?? null,
        'region' => $location['regionName'] ?? null,
        'city' => $location['city'] ?? null,
        'zip' => $location['zip'] ?? null,
        'latitude' => $location['lat'] ?? null,
        'longitude' => $location['lon'] ?? null,
        'timezone' => $location['timezone'] ?? null,
    ]);

    //send email
    $admin = env('ADMIN_EMAIL');

    if ($admin) {
            $photo = PhotoDetail::where('random_id', $random_id)->first();
            if($photo){
               $slot = '
                <p>Dear Admin,</p>

                <p>A new photo report has been submitted on the system. Please find the details below:</p>

                <hr>

                <p><strong>Photo Details:</strong></p>
                <p><strong>Photo ID:</strong> '.$photo->random_id.'</p>
                <hr>
                <p><strong>Reporter Information:</strong></p>
                <p><strong>Name:</strong> '.$request->name.'</p>
                <p><strong>Email:</strong> '.$request->email.'</p>
                <p><strong>Message:</strong><br>'.nl2br(e($request->message)).'</p>
                <p><strong>IP Address:</strong>'.$ip.'</p>
                <p><strong>Browser:</strong>'.$browser.'</p>
                <p><strong>Device:</strong>'.$device.'</p>
                <p><strong>Country:</strong>'.$location['country'] .'</p>
                <p><strong>City:</strong>'.$location['city'] .'</p>

                <hr>

                <p><strong>Photo Preview:</strong></p>
                <p>
                    <img src="'.$photo->photo_url.'" width="300" style="max-width:100%; border:1px solid #ddd; padding:5px;">
                </p>

                <hr>

                <p>Please review this report at your earliest convenience.</p>

                <p>Regards,<br>
                Photo Proof System</p>
            ';
           
        Notification::route('mail', env('ADMIN_EMAIL'))
            ->notify(new CommonMailNotification(
                'New Photo Report - '.$photo->random_id,
                $slot
            ));
        }
        
    }
    return redirect()->route('thank-you');
    }

    public function report_photo(){

    }

    public function download($id)
{
    $photo = PhotoDetail::findOrFail($id);

    $path = storage_path('app/public/' . $photo->photo);

    if (!file_exists($path)) {
        abort(404, 'File not found');
    }

    return response()->download($path);
}
    

}
