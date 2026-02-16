<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;

class DataTableController extends Controller
{
    public function photos($user_id)
    {
        return view('admin.photos.index', compact('user_id'));  // Pass user_id to the view
    }

    // Fetch photos for a specific user by user_id
    public function getData(Request $request, $user_id)
    {
        // Fetch photos for the specific user
        $query = Photo::where('user_id', $user_id);  // Assuming the 'user_id' is stored in the photos table

        // Filter columns if necessary
        if ($request->has('columns')) {
            $columns = explode(',', $request->input('columns'));
            $query->select($columns);
        }

        // Return the data as DataTables response
        return DataTables::of($query)
            ->editColumn('images', function ($row) {
                // Assuming 'images' is a JSON field storing multiple image paths
                $images = json_decode($row->images, true);
                $imageHtml = '';
                if (is_array($images)) {
                    foreach ($images as $image) {
                        $imageHtml .= '<img src="' . asset('storage/' . $image) . '" alt="Image" width="50" style="margin-right: 5px;">';
                    }
                }
                return $imageHtml;
            })
            ->make(true);
    }


}
