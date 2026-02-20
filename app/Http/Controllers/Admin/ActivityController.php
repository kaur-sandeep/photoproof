<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminActivityLog;
use Yajra\DataTables\DataTables;
class ActivityController extends Controller
{
    public function index()
    {
        return view('admin.activity.index');
    }

    public function list()
    {
        $logs = AdminActivityLog::with('admin')->get();
        return DataTables::of($logs)
        ->addIndexColumn()
        ->addColumn('admin', function ($logs) {
            return $logs->admin->name ?? '--';
        })
        ->addColumn('action', function ($logs) {
            return $logs->action ?? '-';
        })

        ->addColumn('module', function ($logs) {
            return $logs->module ?? '-';
        })

        ->addColumn('description', function ($logs) {
            return $logs->description ?? '--';
        })
        ->addColumn('ip_address', function ($logs) {
        return $logs->ip_address ?? '-';
        })
        ->addColumn('date', function ($logs) {
        return $logs->created_at ?? '-';
        })
        ->rawColumns(['admin','action','module','description','ip_address','date'])
        ->make(true);
    }
}
