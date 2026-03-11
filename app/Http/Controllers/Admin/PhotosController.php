<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PhotoView;
use App\Models\PhotoDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ActivityLogger;
use App\Helpers\DateTime;
use Illuminate\Support\Facades\Auth;
use App\Models\PhotoReport;
use Illuminate\Support\Str;

class PhotosController extends Controller
{

    public function index(){
     
        return view('admin.photos.index');
    }

  public function list(Request $request){
     $isSuperAdmin  = auth()->check() && auth()->user()->getRoleNames()->contains('super-admin');
    // $photos = PhotoDetail::with('user')->where('state', '!=', -1)->get();
    $photos = PhotoDetail::with('user.photo_upload_tracks') // load user and their uploadTrack
    ->where('state', '!=', -1)
    ->orderBy('created_at', 'desc')
    ->get();

      $data = [];
        $serialNumber = 1;
     foreach ($photos as $photo) {
        $user = $photo->user;
            foreach ($user->photo_upload_tracks as $track ) {
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
    return DataTables::of($photos)
        ->addIndexColumn()

        // ->addColumn('photo', function ($photo) {
        //     // if ($photo->photo) {
        //     //     return '<img src="'.asset('storage/'.$photo->photo).'" width="50" height="50">';
        //     // }
        //     // return 'No Image';
        //     return $photo->photo
        //     ? '<button class="btn btn-sm btn-primary viewTrackBtn" style="padding:0; border:none; background:none;">
        //     <img src="'.asset('storage/'.$photo->photo).'" width="80" height="80" style="border-radius:5px;">
        //     </button>'
        //     : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
        // })
        ->addColumn('photo', function ($photo) {
            $image = $photo->photo 
                ? asset('storage/'.$photo->photo)
                : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';

            return '<a href="'.route('admin.photos.show', $photo->id).'">
                        <img src="'.$image.'" width="80" height="80" style="border-radius:5px;">
                    </a>';
        })
        ->rawColumns(['photo'])
        ->addColumn('random_id', function ($photo) {
            return $photo->random_id ?? '-';
        })

        ->addColumn('name', function ($photo) {
            return $photo->name ?? '-';
        })

        ->addColumn('location', function ($photo) {
            return $photo->location ?? '-';
        })

        ->addColumn('user_name', function ($photo) {
            return $photo->user ? $photo->user->email : '--';
        })
        ->addColumn('created_at', function ($photo) {
        return DateTime::dateFormat($photo->created_at) ?? '-';
        })

        // ->addColumn('view_count', function ($photo) {
        //     $count=  $photo->view_count ?? 0;
            
        // })

        ->addColumn('view_count', function ($photo) {
        $count=  $photo->view_count ?? 0;
                return '<span class="badge bg-info" style="
                    font-size: 1.2rem; 
                    padding: 0.6em 1em; 
                    text-decoration: none; 
                    border-radius: 0.5rem;
                    display: inline-block;
                "><a href="'.route('admin.photos.show', $photo->id).'" class="badge bg-info">
            '.$count.'
        </a></span>';

            })
    
    ->addColumn('status', function ($photo) use ($isSuperAdmin) {
        if ($isSuperAdmin) {
            if ($photo->state == 1) {
                return '<button class="btn btn-sm btn-success toggle-state" data-id="'.$photo->id.'" data-state="0">Active</button>';
            }
            if ($photo->state == 0) {
                return '<button class="btn btn-sm btn-warning toggle-state" data-id="'.$photo->id.'" data-state="1">Inactive</button>';
                
            }
        }else{
            return 'No Permission';
        }
    })
    ->addColumn('upload_track_record', function ($row) {
                return '<button class="btn btn-sm btn-primary viewTrackBtn">View Track</button>';
    })

    // ->addColumn('actions', function ($photo) {
    //         // return '<a href="'.route('admin.photos.show', $photo->id).'" class="btn btn-sm btn-primary">View</a>
    //         //         <a href="'.route('admin.photos.edit', $photo->id).'" class="btn btn-sm btn-warning">Edit</a>
    //         //         <button class="btn btn-sm btn-danger delete-user" data-id="'.$photo->id.'">Delete</button>';
    //         return '<button class="btn btn-sm btn-danger delete-user" data-id="'.$photo->id.'">Delete</button>';
    //     })

    ->rawColumns(['photo','actions','status','view_count','upload_track_record'])
    ->make(true);
    }

    public function show(Request $request,$id){
        $photos = PhotoDetail::with('user')->find($id);
        $count = $photos->view_count;
// $count = $user->photos_count;


        return view('admin.photos.photo_data',compact('id','count'));
    }

    public function showdata(Request $request,$id){
    $photoViews = PhotoView::where('photo_detail_id', $id)
                    ->with('photo')
                    ->orderBy('created_at','desc')
                    ->get();

      
    return DataTables::of($photoViews)
        ->addIndexColumn()
        ->addColumn('ip_address', function ($photoViews) {
            return $photoViews->ip_address ?? '-';
        })

        ->addColumn('browser', function ($photoViews) {
            return $photoViews->browser ?? '-';
        })

        ->addColumn('platform', function ($photoViews) {
            return $photoViews->platform ?? '-';
        })

        ->addColumn('device', function ($photoViews) {
             return $photoViews->device ?? '-';
        })
        
        ->addColumn('device_type', function ($photoViews) {
            return $photoViews->device_type ?? '-';
        })

        ->addColumn('referer', function ($photoViews) {
            return $photoViews->referer ?? '-';
        })

        ->addColumn('user_agent', function ($photoViews) {
             return $photoViews->user_agent ?? '-';
        })
        ->addColumn('country', function ($photoViews) {
             return $photoViews->country ?? '-';
        })

      ->addColumn('country_code', function ($photoViews) {
            return $photoViews->country_code ?? '-';
        })

        ->addColumn('region', function ($photoViews) {
             return $photoViews->region ?? '-';
        })
        
        ->addColumn('region_name', function ($photoViews) {
            return $photoViews->region_name ?? '-';
        })

        ->addColumn('city', function ($photoViews) {
            return $photoViews->city ?? '-';
        })

        ->addColumn('zip', function ($photoViews) {
             return $photoViews->zip ?? '-';
        })
        ->addColumn('latitude', function ($photoViews) {
             return $photoViews->latitude ?? '-';
        })->addColumn('longitude', function ($photoViews) {
            return $photoViews->longitude ?? '-';
        })

        ->addColumn('timezone', function ($photoViews) {
             return $photoViews->timezone ?? '-';
        })
        
        ->addColumn('isp', function ($photoViews) {
            return $photoViews->isp ?? '-';
        })

        ->addColumn('org', function ($photoViews) {
            return $photoViews->org ?? '-';
        })

        ->addColumn('as_name', function ($photoViews) {
             return $photoViews->as_name ?? '-';
        })
->addColumn('created_at', function ($photoViews) {
    return DateTime::dateFormat($photoViews->created_at) ?? '-';
})
        ->rawColumns(['photoViews'])
        ->make(true);
    }                
    

//     public function showdata(Request $request, $id)
// {
   
//     $photoViews = PhotoView::where('photo_detail_id', $id)
//         ->with('photo'); // Do NOT call get()

//     return DataTables::of($photoViews)
//         ->addIndexColumn()
//         ->addColumn('ip_address', fn($row) => $row->ip_address ?? '-')
//         ->addColumn('browser', fn($row) => $row->browser ?? '-')
//         ->addColumn('platform', fn($row) => $row->platform ?? '-')
//         ->addColumn('device', fn($row) => $row->device ?? '-')
//         ->addColumn('device_type', fn($row) => $row->device_type ?? '-')
//         ->addColumn('referer', fn($row) => $row->referer ?? '-')
//         ->addColumn('user_agent', fn($row) => $row->user_agent ?? '-')
//         ->addColumn('country', fn($row) => $row->country ?? '-')
//         ->addColumn('country_code', fn($row) => $row->country_code ?? '-')
//         ->addColumn('region', fn($row) => $row->region ?? '-')
//         ->addColumn('region_name', fn($row) => $row->region_name ?? '-')
//         ->addColumn('city', fn($row) => $row->city ?? '-')
//         ->addColumn('zip', fn($row) => $row->zip ?? '-')
//         ->addColumn('latitude', fn($row) => $row->latitude ?? '-')
//         ->addColumn('longitude', fn($row) => $row->longitude ?? '-')
//         ->addColumn('timezone', fn($row) => $row->timezone ?? '-')
//         ->addColumn('isp', fn($row) => $row->isp ?? '-')
//         ->addColumn('org', fn($row) => $row->org ?? '-')
//         ->addColumn('as_name', fn($row) => $row->as_name ?? '-')
//         ->filterColumn('ip_address', fn($query, $keyword) => $query->where('ip_address', 'like', "%{$keyword}%"))
//         ->filterColumn('browser', fn($query, $keyword) => $query->where('browser', 'like', "%{$keyword}%"))
//         ->filterColumn('platform', fn($query, $keyword) => $query->where('platform', 'like', "%{$keyword}%"))
//         ->filterColumn('device', fn($query, $keyword) => $query->where('device', 'like', "%{$keyword}%"))
//         ->filterColumn('device_type', fn($query, $keyword) => $query->where('device_type', 'like', "%{$keyword}%"))
//         ->filterColumn('referer', fn($query, $keyword) => $query->where('referer', 'like', "%{$keyword}%"))
//         ->filterColumn('user_agent', fn($query, $keyword) => $query->where('user_agent', 'like', "%{$keyword}%"))
//         ->filterColumn('country', fn($query, $keyword) => $query->where('country', 'like', "%{$keyword}%"))
//         ->filterColumn('country_code', fn($query, $keyword) => $query->where('country_code', 'like', "%{$keyword}%"))
//         ->filterColumn('region', fn($query, $keyword) => $query->where('region', 'like', "%{$keyword}%"))
//         ->filterColumn('region_name', fn($query, $keyword) => $query->where('region_name', 'like', "%{$keyword}%"))
//         ->filterColumn('city', fn($query, $keyword) => $query->where('city', 'like', "%{$keyword}%"))
//         ->filterColumn('zip', fn($query, $keyword) => $query->where('zip', 'like', "%{$keyword}%"))
//         ->filterColumn('latitude', fn($query, $keyword) => $query->where('latitude', 'like', "%{$keyword}%"))
//         ->filterColumn('longitude', fn($query, $keyword) => $query->where('longitude', 'like', "%{$keyword}%"))
//         ->filterColumn('timezone', fn($query, $keyword) => $query->where('timezone', 'like', "%{$keyword}%"))
//         ->filterColumn('isp', fn($query, $keyword) => $query->where('isp', 'like', "%{$keyword}%"))
//         ->filterColumn('org', fn($query, $keyword) => $query->where('org', 'like', "%{$keyword}%"))
//         ->filterColumn('as_name', fn($query, $keyword) => $query->where('as_name', 'like', "%{$keyword}%"))

//         ->make(true);
// }


    public function edit(Request $request,$id){
        $photo = PhotoDetail::findOrFail($id);
        return view('admin/photos/edit',compact('photo'));

    }

public function update(Request $request, $photo_id)
{
    $photo = PhotoDetail::findOrFail($photo_id);

    // Validate incoming request
    $request->validate([
        'name'   => 'required|string|max:255',
        'photo'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Make sure the field name is 'photo'
    ]);

    // Update photo name
    $photo->name = $request->name;

    // Handle file upload if present
    if ($request->hasFile('photo') && $request->file('photo')->isValid()) {

        // Delete old image if it exists
        if ($photo->photo && Storage::disk('public')->exists('profile/' . $photo->photo)) {
            Storage::disk('public')->delete('profile/' . $photo->photo);
        }

        // Store new image
        $path = $request->file('photo')->store('profile', 'public');
        $photo->photo = basename($path);  // Store only the filename
    }

    // Save updated photo details
    $photo->save();

    return redirect()->back()->with('success', 'Photo updated successfully!');
}



    public function updateStatus(Request $request){
    $id = $request->input('id');
    $status = $request->input('state');
//    dd($id,$status);
    $admin = Auth::user();
    // dd($id,$status);
        $request->validate([
            'id' => 'required',
            'state' => 'required|in:-1,0,1'
        ]);

        $photo = PhotoDetail::findOrFail($id);
        $oldStatus = $photo->state;
        $photo->state = $status;
        $photo->save();
         $statusText = [
            -1 => 'Deleted',
            0  => 'Inactive',
            1  => 'Active',
        ];

        // ✅ Activity Log
        ActivityLogger::log(
            'Update',
            'Admin Photos',
            'Changed status of photo ' . $photo->name .
            ' from ' . ($statusText[$oldStatus] ?? $oldStatus) .
            ' to ' . ($statusText[$status] ?? $status)
        );
        return response()->json([
            'success' => true,
            'message' => 'Photo status updated successfully'
        ]);
    }

    public function reportedImages(){
        return view('admin.report.index');
    }

    public function reportedImagesList(){
        $reported_data = PhotoReport::orderBy('created_at', 'desc')->get();
        return DataTables::of($reported_data)
        ->addIndexColumn()
        ->addColumn('image', function ($reported_data) {
        $photo = PhotoDetail::where('random_id', $reported_data->photo_random_id)->first();
        $default = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
        $image = !empty($photo?->photo) ? asset('storage/'.$photo->photo) : $default;
      
        return '<img src="' . ($image
            ? asset('storage/' .$image)
            : $default) . '" width="40" height="40" class="rounded-circle">';
        })
        ->addColumn('photo_random_id', function ($reported_data) {
            return $reported_data->photo_random_id ?? '--';
        })
        ->addColumn('photo_random_id', function ($reported_data) {
            return $reported_data->photo_random_id ?? '--';
        })
        ->addColumn('name', function ($reported_data) {
            return $reported_data->name ?? '-';
        })
        ->addColumn('email', function ($reported_data) {
            return $reported_data->email ?? '-';
        })

        ->addColumn('message', function ($reported_data) {
            return $reported_data->message 
                ? Str::limit($reported_data->message, 100, '...') 
                : '--';
        })
        ->addColumn('ip_address', function ($reported_data) {
        return $reported_data->ip_address ?? '-';
        })
        ->addColumn('device', function ($reported_data) {
        return $reported_data->device ?? '-';
        })
        ->addColumn('device_type', function ($reported_data) {
        return $reported_data->device_type ?? '-';
        })
        ->addColumn('country', function ($reported_data) {
        return $reported_data->country ?? '-';
        })
        ->addColumn('region', function ($reported_data) {
        return $reported_data->region ?? '-';
        })
        ->addColumn('city', function ($reported_data) {
        return $reported_data->city ?? '-';
        })
        ->addColumn('zip', function ($reported_data) {
        return $reported_data->zip ?? '-';
        })
        ->addColumn('created_at', function ($reported_data) {
        return DateTime::dateFormat($reported_data->created_at) ?? '-';
        })
         ->addColumn('actions', function ($reported_data) {
            // return '<a href="'.route('reported.show', $reported_data->id).'" class="btn btn-sm btn-primary">View</a>';
            $browser = $reported_data->browser?? '';
            $platform =$reported_data->platform?? '';
            $deviceType = $reported_data->device_type?? '';
            if (!empty($reported_data->country) && 
                !empty($reported_data->region) && 
                !empty($reported_data->city) && 
                !empty($reported_data->zip)) {

                $location = implode(',', [
                    $reported_data->country,
                    $reported_data->region,
                    $reported_data->city,
                    $reported_data->zip
                ]);

            } else {
                $location = '';
            }
        //     return '<button 
        //     class="btn btn-primary viewReported"
        //     data-name="'.$reported_data->name.'"
        //     data-email="'.$reported_data->email.'"
        //     data-message="'.$reported_data->message.'"
        //     data-browser="'.$browser.'"
        //     data-platform="'.$platform.'"
        //     data-devicetype="'.$deviceType.'"
        //     data-ip="'.$reported_data->ip_address.'"
        //     data-date="'.DateTime::dateFormat($reported_data->created_at).'"
        //     data-location="'.$location.'"
        //     data-bs-toggle="modal"
        //     data-bs-target="#ReportModal">
        //     View
        // </button>';



         return '<button 
            class="btn btn-primary viewReported"
            data-name="'.$reported_data->name.'"
            data-email="'.$reported_data->email.'"
            data-message="'.$reported_data->message.'"
            data-browser="'.$browser.'"
            data-platform="'.$platform.'"
            data-devicetype="'.$deviceType.'"
            data-ip="'.$reported_data->ip_address.'"
            data-date="'.DateTime::dateFormat($reported_data->created_at).'"
            data-location="'.$location.'"
            data-bs-toggle="modal"
            data-bs-target="#ReportModal">
            View
        </button>';
        })
        ->rawColumns(['image','photo_random_id','created_at','actions'])
        ->make(true);
    }



}
