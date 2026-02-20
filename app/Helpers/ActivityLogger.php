<?php

namespace App\Helpers;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $module, $description = null)
    {
        if (Auth::guard('admin')->check()) {
            AdminActivityLog::create([
                'admin_id'   => Auth::guard('admin')->id(),
                'action'     => $action,
                'module'     => $module,
                'description'=> $description,
                'ip_address' => request()->ip(),
            ]);
        }
    }
}