<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoReport;
use App\Models\Notifications;
use App\Helpers\DateTime;
use Yajra\DataTables\DataTables;
class PhotoNotificationController extends Controller
{
    // public function getUnreadNotifications()
    // {
    //     $notifications = PhotoReport::where('is_read', 0)
    //         ->orderBy('created_at', 'desc')
    //         ->take(5)
    //         ->get();

    //     return response()->json($notifications);
    // }

    public function getUnreadNotifications()
{
    // $notifications = PhotoReport::where('is_read', 0)
        $notifications = Notifications::where('is_read', 0)    
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get()
        ->map(function ($item) {

            $item->created_at_formatted = $item->created_at
                ? DateTime::dateFormat($item->created_at)
                : '--';

            return $item;
        });

    return response()->json($notifications);
}

    public function markAsRead($id)
{
    // $notification = PhotoReport::findOrFail($id);
    $notification = Notifications::findOrFail($id);
    $notification->is_read = 1;
    $notification->save();

    return response()->json(['status' => 'success']);
}

    public function notifications()
    {
        // $notifications = PhotoReport::orderBy('created_at', 'desc')->paginate(10);
        $notifications = Notifications::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        
        // Find the notification or fail
        // $notification = PhotoReport::findOrFail($id);
        $notification = Notifications::findOrFail($id);
        // Mark as read if not already
        if (!$notification->is_read) {
            $notification->is_read = 1;
            $notification->save();
        }
        // Return a view and pass the notification
        return view('admin.notifications.show', compact('notification'));
    }

     public function reportShow($id)
    {
        // Find the notification or fail
        $reportdata = PhotoReport::findOrFail($id);
    
        // Mark as read if not already
        if (!$reportdata->is_read) {
            $reportdata->is_read = 1;
            $reportdata->save();
        }

        // Return a view and pass the notification
        return view('admin.report.show', compact('reportdata'));
    }
    


    public function list(Request $request){
        
   $notifications = notifications::query();

// Check if there's a custom search query
$customSearch = request()->input('type'); // Assuming the custom search field is called 'custom_search'

// If there's a custom search, add it to the query
if ($customSearch) {
    $notifications->where(function ($query) use ($customSearch) {
        $query->where('name', 'like', '%' . $customSearch . '%')
              ->orWhere('email', 'like', '%' . $customSearch . '%')
              ->orWhere('type', 'like', '%' . $customSearch . '%');
    });
}

$notifications = $notifications->get();

return DataTables::of($notifications)
    ->addIndexColumn()
    ->addColumn('photo_random_id', function ($notifications) {
        return $notifications->photo_random_id ?? '--';
    })
    ->addColumn('name', function ($notifications) {
        return $notifications->name ?? '-';
    })
    ->addColumn('email', function ($notifications) {
        return $notifications->email ?? '-';
    })
    ->addColumn('type', function ($notifications) {
        return ucwords($notifications->type) ?? '--';
    })
    ->addColumn('ip_address', function ($notifications) {
        $data = json_decode($notifications->data, true);
        return $data['ip'] ?? '-';
    })
    ->addColumn('date', function ($notifications) {
        $date = DateTime::dateFormat($notifications->created_at);
        return $date;
    })
    ->addColumn('actions', function ($notifications) {
        return '<a href="'.route('notifications.show', $notifications->id).'" class="btn btn-sm btn-primary">View</a>';
    })
    ->rawColumns(['name','actions','email','type','ip_address','date'])
    ->make(true);
}
}
