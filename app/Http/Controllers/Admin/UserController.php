<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\PhotoDetail;
use Yajra\DataTables\DataTables;
use App\Helpers\ActivityLogger;
use App\Helpers\DateTime;


class UserController extends Controller
{

    
//    public function index()
//     {
//         return view('admin.users.index');
//     }

    public function index(){
        return view('admin.users.index');
    }
    
    
    // public function list(Request $request)
    // {
    //     return $this->getData(
    //         $request,
    //         User::class,
    //         ['name', 'email', 'profile_image', 'phone_number']
    //     );
    // }

    // public function list(Request $request){
    // $users = User::withCount('photos')->get();
    // return DataTables::of($users)
    //     ->addIndexColumn()
    //     // ->addColumn('profile_image', function ($user) {
    //     //     return $user->profile_image
    //     //         ? '<img src="'.asset('storage/profile/'.$user->profile_image).'" width="40" height="40" class="rounded-circle">'
    //     //         : '<span class="text-muted">No Image</span>';
    //     // })
    //     ->addColumn('profile_image', function ($user) {
    //             $default = "https://cdn-icons-png.flaticon.com/512/149/149071.png";

    //             return '<img src="'
    //                 . ($user->profile_image 
    //                     ? asset('storage/profile/' . $user->profile_image) 
    //                     : $default)
    //                 . '" width="40" height="40" class="rounded-circle">';
    //         })
    //    ->addColumn('device', function ($user) {
    //         return $user->device ?? '--'; // if device is null, show --
    //     })
    //     ->addColumn('created_at', function ($user) {
    //         return $user->created_at ?? '--'; // if device is null, show --
    //     })
    //     ->addColumn('photo_count', function ($user) {
    //         return '<span class="badge bg-info" style="
    //               font-size: 1.2rem; 
    //               padding: 0.6em 1em; 
    //               text-decoration: none; 
    //               border-radius: 0.5rem;
    //               display: inline-block;
    //           " ><a href="'.route('admin.users.show.imagedata', $user->id).'" class="badge bg-info">
    //          '.$user->photos_count.'
    //         </a></span>';
    //     })
    // // ->addColumn('status', function ($user) {
    // //         if ($user->status == 1) {
    // //             return '<button class="btn btn-sm btn-warning toggle-status" data-id="'.$user->id.'" data-status="0">Inactive</button>';
    // //         }
    // //         if ($user->status == 0) {
    // //             return '<button class="btn btn-sm btn-success toggle-status" data-id="'.$user->id.'" data-status="1">Active</button>';
                
    // //         }
    // //         return '<span class="badge bg-danger">Deleted</span>';
    // // })
    //     // ->addColumn('actions', function ($user) {
    //     //     // return '<a href="'.route('admin.users.show.imagedata', $user->id).'" class="btn btn-sm btn-primary">View</a>
    //     //     //         <a href="'.route('admin.users.edit', $user->id).'" class="btn btn-sm btn-warning">Edit</a>
    //     //     //         <button class="btn btn-sm btn-danger delete-user" data-id="'.$user->id.'">Delete</button>';
    //     //     return '<a href="'.route('admin.users.edit', $user->id).'" class="btn btn-sm btn-warning">Edit</a>
    //     //             <button class="btn btn-sm btn-danger delete-user" data-id="'.$user->id.'">Delete</button>';
    //     // })
    //     // ->rawColumns(['profile_image', 'photo_count', 'status', 'actions'])
    //     ->rawColumns(['profile_image', 'photo_count'])
    //     ->make(true);
    // }


    public function list(Request $request){
    $users = User::with('photos.uploadTrack')->withCount('photos')->orderBy('created_at', 'desc')->get();
  

return DataTables::of($users)
    ->addIndexColumn()
    ->addColumn('name', fn($user) => $user->name ?? '--')
    ->addColumn('email', fn($user) => $user->email ?? '--')
    ->addColumn('profile_image', function ($user) {
        $default = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
        return '<img src="' . ($user->profile_image
            ? asset('storage/profile/' . $user->profile_image)
            : $default) . '" width="40" height="40" class="rounded-circle">';
    })
  ->addColumn('country', function ($user) {
        $track = $user->photos->firstWhere('uploadTrack', '!=', null)?->uploadTrack;
        return $track?->country ?? '--';
    })
    ->addColumn('city', function ($user) {
        $track = $user->photos->firstWhere('uploadTrack', '!=', null)?->uploadTrack;
        return $track?->city ?? '--';
    })
    ->addColumn('state', function ($user) {
        $track = $user->photos->firstWhere('uploadTrack', '!=', null)?->uploadTrack;
        return $track?->region_name ?? '--';
    })
    ->addColumn('zip', function ($user) {
        $track = $user->photos->firstWhere('uploadTrack', '!=', null)?->uploadTrack;
        return $track?->zip ?? '--';
    })
    ->addColumn('device', function ($user) {
        $track = $user->photos->firstWhere('uploadTrack', '!=', null)?->uploadTrack;
        return $track?->device_type ?? '--';
    })
        ->addColumn('timezone', function ($user) {
        $track = $user->photos->firstWhere('uploadTrack', '!=', null)?->uploadTrack;
        return $track?->timezone ?? '--';
    })
    ->addColumn('created_at', fn($user) => DateTime::dateFormat($user->created_at)?? '--')
     ->addColumn('photo_count', function ($user) {
            return '<span class="badge bg-info" style="
                  font-size: 1.2rem; 
                  padding: 0.6em 1em; 
                  text-decoration: none; 
                  border-radius: 0.5rem;
                  display: inline-block;
              " ><a href="'.route('admin.users.show.imagedata', $user->id).'" class="badge bg-info">
             '.$user->photos_count.'
            </a></span>';
        })
    ->rawColumns(['profile_image', 'photo_count'])
    ->make(true);
    }
    public function create(){
        return view('admin/users/add');
    }
    
    public function store(Request $request){
       $request->validate([
        'name'   => 'required|string|max:255',
        'email'  => 'required|email|unique:users,email',
        'number' => 'required|numeric|digits_between:10,14',
        'password' => 'required|min:6',
        'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $imageName = null;
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile', 'public');
            $imageName = basename($path);
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->number,
            'password' => Hash::make($request->password),
            'profile_image' => $imageName,
        ]);

        return redirect()->back()->with('success', 'User added successfully!');
    }

    public function edit(Request $request,$user_id){
        $user = User::findOrFail($user_id);
        return view('admin/users/edit',compact('user'));

    }

    public function update(Request $request,$user_id){
        $user = User::findOrFail($user_id);
        $request->validate([
            'name'   => 'required|string|max:255',
            // 'email'  => 'required|email|max:255|unique:users,email',
            'number' => 'required|numeric|digits_between:10,14',
            'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;
        // $user->emarenderRecordCountil = $request->email;
        $user->phone_number = $request->number;

        if ($request->hasFile('profile_image')) {

            // Delete old image
            if ($user->profile_image && Storage::disk('public')->exists('profile/' . $user->profile_image)) {
                Storage::disk('public')->delete('profile/' . $user->profile_image);
            }

            // Store new image
            $path = $request->file('profile_image')->store('profile', 'public');
            $user->profile_image = basename($path);
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    // public function updateStatus(Request $request){
    // $id = $request->input('id');
    // $status = $request->input('status');
    //     $request->validate([
    //         'id' => 'required|exists:users,id',
    //         'status' => 'required|in:-1,0,1'
    //     ]);

    //     $status = (string) $request->status;
    //     $user = User::findOrFail($request->id);
    //     if ($status == -1) {
    //         $user->delete();
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User deleted successfully'
    //         ]);
    //     }else{
    //         $user->status = $status;
    //         $user->save();
    //     }
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User status updated successfully'
    //     ]);
    // }


    public function updateStatus(Request $request){
        $id = $request->input('id');
        $status = $request->input('status');
        $request->validate([
            'id' => 'required',
            'status' => 'required|in:-1,0,1'
        ]);

        $status = (string) $request->status;
        $user = User::findOrFail($request->id);
        $user->state = $status;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully'
        ]);
    }


  
    public function viewImages()
    {
        return view('admin.users.showImages');
    }

    public function getUsersWithImages(Request $request)
    {
        $users = User::with('photos.views', 'photos.uploadTrack', 'photo_upload_tracks.photo')
            ->when($request->name, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->when($request->user_id, function ($query) use ($request) {
                return $query->where('id', $request->user_id);  // Filter by user_id
            })
            ->get();
        return DataTables::of($users)

            // ✅ Upload Track Column
            ->addColumn('upload_track_details', function ($user) {
                if ($user->photo_upload_tracks->isEmpty()) {
                    return 'No Upload Data';
                }

                $html = '';

                foreach ($user->photo_upload_tracks as $track) {
                    $html .= "<div style='font-size:13px; margin-bottom:8px'>";
                    $html .= "<b>IP:</b> {$track->ip_address}<br>";
                    $html .= "<b>City:</b> {$track->city}<br>";
                    $html .= "<b>Country:</b> {$track->country}<br>";
                    $html .= "<b>Device:</b> {$track->device_type}<br>";
                    $html .= "<b>ISP:</b> {$track->isp}<br>";
                    $html .= "<hr></div>";
                }

                return $html;
            })

            // ✅ Images Column
            ->addColumn('images', function ($user) {
                if ($user->photos->isEmpty()) {
                    return 'No Images';
                }

                $html = '';

                foreach ($user->photos as $photo) {
                    // Ensure the photo exists before rendering
                    if (!empty($photo->photo)) {
                        $html .= '<img src="' . asset('storage/profile/' . $photo->photo) . '" 
                                    width="60" height="60" 
                                    style="margin:5px; border-radius:5px;">';
                    }
                }

                // Return HTML with fallback if no images
                return $html ?: 'No Images';
            })
            
            // Remove the actions column from the backend as well
            ->rawColumns(['upload_track_details', 'images']) // Only rawColumns for the existing columns
            ->make(true);
    }


    public function showImagedatawithid(Request $request,$id){
            $user = User::findOrFail($request->id);
            return view('admin.users.showphotos',compact('user'));
    }
    public function getUsersWithImageswithId(Request $request, $id)
    {
        $users = User::with('photos.uploadTrack')
            ->when($request->name, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->when($id, function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->get();
        $data = [];
        $serialNumber = 1;

        foreach ($users as $user) {
            foreach ($user->photos as $photo) {

                $track = $photo->uploadTrack;

                $data[] = [
                    'name'=>$user->name,
                    'created_at'=>DateTime::dateFormat($photo->created_at),
                    'email'=>$user->email,
                    'random_id' => $photo->random_id,
                    'serial_number' => $serialNumber++,
                    'photo_id' => $photo->id,
                    'user_email' => $user->email,
                    'view_count' => $photo->view_count ?? 0,
                    'image' => $photo->photo ? asset('storage/' . $photo->photo) : '',
                    'date_time' => DateTime::dateFormat($photo->word_api_date_time) ?? '',
                    'location' => $photo->location ?? '',
                    // 'country' => isset($photo->country) ? $photo->country : (isset($track->country) ? $track->country : ''),
                    // 'region' => isset($photo->region_name) ? $photo->region_name : (isset($track->region_name) ? $track->region_name : ''),
                    // 'city' => isset($photo->city) ? $photo->city : (isset($track->city) ? $track->city : ''),
                    // 'zip' => isset($photo->zip) ? $photo->zip : (isset($track->zip) ? $track->zip : ''),
                    'timezone' => isset($photo->timezone) ? $photo->timezone : (isset($track->timezone) ? $track->timezone : ''),
                    'latitude' => isset($photo->latitude) 
                        ? number_format($photo->latitude, 8, '.', '') 
                        : (isset($track->latitude) ? number_format($track->latitude, 8, '.', '') : null),

                    'longitude' => isset($photo->longitude) 
                        ? number_format($photo->longitude, 8, '.', '') 
                        : (isset($track->longitude) ? number_format($track->longitude, 8, '.', '') : null),
                    'ip_address' => $track->ip_address ?? '',
                    'device_type' => $photo->device_type ?? '',
                    'device_brand' => $photo->device_brand ?? '',
                    'device_model' => $photo->device_model ?? '',
                    'device_name' => $photo->device_name ?? '',
                    'device_manufacturer' => $photo->device_manufacturer ?? '',
                    'android_version' => $photo->android_version ?? '',
                    'android_sdk' => $photo->android_sdk ?? '',
                    'ios_system_version' => $photo->ios_system_version ?? '',
                    'ios_identifier' => $photo->ios_identifier ?? '',
                    'isp' => $track->isp ?? '',
                    'state' => $photo->state ?? '',
                    
                ];
            }
        }

       return DataTables::of($data)
            ->addColumn('images', function ($row) {
                 $default = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
                // return $row['image']
                //     ? '<img src="' . $row['image'] . '" width="80" height="80" style="border-radius:5px;">'
                //     :  $default;
                  return '<a href="'.route('admin.photos.show', $row['photo_id']).'">
                        <img src="'.$row['image'].'" width="80" height="80" style="border-radius:5px;">
                    </a>';
    //             return $row['image']
    // ? '<button class="btn btn-sm btn-primary viewTrackBtn" style="padding:0; border:none; background:none;">
    //         <img src="' . $row['image'] . '" width="80" height="80" style="border-radius:5px;">
    //    </button>'
    // : 'No Image';
            })
            ->addColumn('view_count', function ($row) {
                return '<span class="badge bg-info" style="
                        font-size: 1.2rem; 
                        padding: 0.6em 1em; 
                        text-decoration: none; 
                        border-radius: 0.5rem;
                        display: inline-block;
                    "><a href="'.route('admin.photos.show',  $row['photo_id']).'" class="badge bg-info">
                    '.$row['view_count'].'
                    </a></span>';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-primary viewTrackBtn">View Track</button>';
            })
            ->addColumn('status', function ($row) {
            if ($row['state'] == 1) {
                    return '<button class="btn btn-sm btn-success toggle-state" data-id="'.$row['photo_id'].'" data-state="0">Active</button>';
            }
            if ($row['state'] == 0) {
                    return '<button class="btn btn-sm btn-warning toggle-state" data-id="'.$row['photo_id'].'" data-state="1">Inactive</button>';
                    
            }
            })
            ->rawColumns(['images', 'action', 'view_count','status']) // 👈 FIX HERE
            ->make(true);
        }

    public function getUsersWithImageswithIdbkbysamdeep(Request $request,$id)
    {
        $users = User::with('photos.uploadTrack') // Load the relationships
            ->when($request->name, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->when($id, function ($query) use ($id) {
                return $query->where('id', $id);  // Filter by user_id
            })
            ->get();

        $data = []; 
        $serialNumber = 1; 
        // Iterate over each user and create separate rows for each photo
        foreach ($users as $user) {
            foreach ($user->photos as $photo) {
                $track = $photo->uploadTrack;
                
                // Add a row for each photo
                $data[] = [
                    'serial_number' => $serialNumber++, // Increment serial number for each row
                    'user_id' => $user->id,
                    'photo_id'=>$photo->id,
                    // 'user_name' => $user->name,
                    'user_email' => $user->email,
                    'view_count'    => $photo->view_count ?? 0, 
                    'image' => $photo->photo ? asset('storage/' . $photo->photo) : 'No Image Available',
                    'upload_track_details' => $track ? 
                        "<div style='border:1px solid #ddd; padding:10px; margin-bottom:10px;'>
                        <b>Random Id:</b> {$photo->random_id}<br>
                            <b>IP Address:</b> {$track->ip_address}<br>
                            <b>City:</b> {$track->city}<br>
                            <b>Country:</b> {$track->country}<br>
                            <b>Latitude:</b> {$track->latitude}<br>
                            <b>Longitude:</b> {$track->longitude}<br>
                            <b>Zip:</b> {$track->zip}<br>
                            <b>Device:</b> {$track->device_type}<br>
                            <b>ISP:</b> {$track->isp}<br>
                            <b>Upload Time:</b> " . DateTime::dateFormat($track->created_at) . "<br>
                        </div>" : 
                        "<span style='color:red;'>No Upload Track Found</span>"
                ];
            }
        }
        return DataTables::of($data)
            ->addColumn('serial_number', function ($row) {
                return $row['serial_number'];  // Return the serial number
            })
            ->addColumn('images', function ($row) {
                return $row['image'] ? '<img src="' . $row['image'] . '" width="80" height="80" style="margin-bottom:5px; border-radius:5px;">' : 'No Image Available';
            })
            ->addColumn('view_count', function ($row) {
                return '<span class="badge bg-info" style="
                        font-size: 1.2rem; 
                        padding: 0.6em 1em; 
                        text-decoration: none; 
                        border-radius: 0.5rem;
                        display: inline-block;
                    "><a href="'.route('admin.photos.show',  $row['photo_id']).'" class="badge bg-info">
                    '.$row['view_count'].'
                    </a></span>';
            })
            ->addColumn('upload_track_details', function ($row) {
                return $row['upload_track_details'];
            })
            ->rawColumns(['images', 'upload_track_details','view_count']) // Ensure raw HTML is returned for both columns
            ->make(true);
    }

}
