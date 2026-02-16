<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PhotoDetail;
use Yajra\DataTables\DataTables;
use App\Traits\DataTableTrait;
use Illuminate\Support\Facades\Storage;
class PhotoController extends Controller
{
     use DataTableTrait;

// public function list(Request $request,$id)
// {
//       $users = User::with('photos')->where('status', '!=', -1)->paginate(10); // Exclude deleted users

//     // Define table headers
//     $headers = ['ID', 'Name', 'Email', 'Photo', 'Images'];

//     // Format rows for the table
//     $rows = $users->map(function ($user) {
//         // Get all images for the user (thumbnails)
//         $images = $user->photos->map(function ($photo) {
//             return '<img src="' . asset('storage/profile/' . $photo->photo) . '" alt="Image" width="50" height="50">';
//         })->implode(' '); // Join images as a single string

//         return [
//             $user->id,
//             $user->name,
//             $user->email,
//             $user->profile_image ? "<img src='".asset('storage/profile/'.$user->profile_image)."' alt='Photo' width='50' height='50'>" : 'No Photo',
//             $images ?: 'No Images',  // Display images or "No Images" if there are none
//         ];
//     });

//     return view('admin.photos.index', compact('headers', 'rows', 'users'));
// }

 public function showPhotosById(Request $request)
    {
        // Fetch photos and associated user data
        $photos = PhotoDetail::with('user') // Assuming PhotoDetail has a 'user' relationship
            ->select('photo_details.id', 'photo_details.image_path', 'users.name', 'users.email', 'users.phone_number')  // Adjust based on your actual table columns
            ->join('users', 'users.id', '=', 'photo_details.user_id') // Join users table to get their info
            ->get();

        // Format data for DataTables
        $data = $photos->map(function ($photo) {
            return [
                's.no' => $photo->id, // Serial number
                'profile_image' => asset('storage/' . $photo->image_path),  // Assuming image_path field in your photos table
                'name' => $photo->user->name, // Get the user's name from the relationship
                'email' => $photo->user->email,  // Get the user's email
                'phone_number' => $photo->user->phone_number,  // Get the user's phone number
                'photos_count' => $photo->user->photos->count(), // Count the photos for the user (this assumes the 'photos' relationship exists on the User model)
            ];
        });

        // Return DataTables format
        return response()->json([
            'data' => $data,
        ]);
    }

}
