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
class PhotosController extends Controller
{

    public function index(){
     
        return view('admin.photos.index');
    }

  public function list(Request $request){
   $photos = PhotoDetail::with('user')->where('state', '!=', -1)->get();
    
return DataTables::of($photos)
    ->addIndexColumn()

    ->addColumn('photo', function ($photo) {
        if ($photo->photo) {
            return '<img src="'.asset('storage/'.$photo->photo).'" width="50" height="50">';
        }
        return 'No Image';
    })

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
       return $photo->created_at ?? '-';
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

->addColumn('status', function ($photo) {
    if ($photo->state == 1) {
        return '<button class="btn btn-sm btn-warning toggle-state" data-id="'.$photo->id.'" data-state="0">Set Inactive</button>';
    }
    if ($photo->state == 0) {
        return '<button class="btn btn-sm btn-success toggle-state" data-id="'.$photo->id.'" data-state="1">Set Active</button>';
        
    }
})

    // ->addColumn('actions', function ($photo) {
    //         // return '<a href="'.route('admin.photos.show', $photo->id).'" class="btn btn-sm btn-primary">View</a>
    //         //         <a href="'.route('admin.photos.edit', $photo->id).'" class="btn btn-sm btn-warning">Edit</a>
    //         //         <button class="btn btn-sm btn-danger delete-user" data-id="'.$photo->id.'">Delete</button>';
    //         return '<button class="btn btn-sm btn-danger delete-user" data-id="'.$photo->id.'">Delete</button>';
    //     })

    ->rawColumns(['photo','actions','status','view_count'])
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
             return $photoViews->created_at ?? '-';
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
    // dd($id,$status);
        $request->validate([
            'id' => 'required|exists:users,id',
            'state' => 'required|in:-1,0,1'
        ]);

        $photo = PhotoDetail::findOrFail($id);
        $photo->state = $status;
        $photo->save();
        return response()->json([
            'success' => true,
            'message' => 'Photo status updated successfully'
        ]);
    }

}
