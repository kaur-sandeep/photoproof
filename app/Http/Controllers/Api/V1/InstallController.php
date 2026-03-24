<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppInstall;
class InstallController extends Controller
{
    public function trackInstall(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'platform'  => 'required|in:android,ios',
            'app_version' => 'nullable|string'
        ]);

        $install = AppInstall::where('device_id', $request->device_id)->first();

        if (!$install) {
            AppInstall::create([
                'device_id' => $request->device_id,
                'platform' => $request->platform,
                'app_version' => $request->app_version
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Application installed',
            ]);
       
        }

        return response()->json([
            'status' => true,
            'message' => 'Application already exist',
        ]);
    }
}
