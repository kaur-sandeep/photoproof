<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminActivityLog;
class ActivityController extends Controller
{
    public function index()
    {
        $logs = AdminActivityLog::with('admin')
                    ->latest()
                    ->paginate(20);

        return view('admin.activity.index', compact('logs'));
    }
}
