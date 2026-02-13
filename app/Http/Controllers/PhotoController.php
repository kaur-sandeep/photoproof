<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PhotoDetail;
use App\Models\PhotoView;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Http;
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
        $photo = PhotoDetail::with('user')
                    ->where('random_id', $random_id)
                    ->first();

        if (!$photo) {
            abort(404);
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
        $alreadyViewed = PhotoView::where('photo_detail_id', $photo->id)
            ->where('ip_address', $ip)
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

        return view('user.photo-view', compact('photo'));
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
}
