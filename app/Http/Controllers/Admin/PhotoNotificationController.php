<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoReport;
use App\Helpers\DateTime;

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
    $notifications = PhotoReport::where('is_read', 0)
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
    $notification = PhotoReport::findOrFail($id);
    $notification->is_read = 1;
    $notification->save();

    return response()->json(['status' => 'success']);
}

    public function notifications()
    {
        $notifications = PhotoReport::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        // Find the notification or fail
        $notification = PhotoReport::findOrFail($id);

        // Mark as read if not already
        if (!$notification->is_read) {
            $notification->is_read = 1;
            $notification->save();
        }

        // Return a view and pass the notification
        return view('admin.notifications.show', compact('notification'));
    }
}
