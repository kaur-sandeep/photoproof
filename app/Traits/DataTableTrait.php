<?php

namespace App\Traits;
use App\Models\User;

use Illuminate\Http\Request;

trait DataTableTrait
{
    // public function getData(Request $request, $model, $searchable = [])
    // {
    //     $query = $model::query();

    //     if ($request->filled('search')) {
    //         $search = $request->search;

    //         $query->where(function ($q) use ($search, $searchable) {
    //             foreach ($searchable as $field) {
    //                 $q->orWhere($field, 'like', "%{$search}%");
    //             }
    //         });
    //     }

    //     return $query->paginate(10);
    // }

    public function getData(Request $request, string $model, array $searchable = [], array $withCounts = [], array $withRelations = [], $perPage = 10)
{
    // Start query
    $query = $model::query();

    // Add counts (like photos_count)
    if (!empty($withCounts)) {
        $query->withCount($withCounts);
    }

    // Add eager loaded relationships if needed
    if (!empty($withRelations)) {
        $query->with($withRelations);
    }

    // Search functionality
    if ($request->filled('search') && !empty($searchable)) {
        $search = $request->search;
        $query->where(function ($q) use ($search, $searchable) {
            foreach ($searchable as $field) {
                $q->orWhere($field, 'like', "%{$search}%");
            }
        });
    }

    // Pagination
    return $query->paginate($perPage)->withQueryString();
}
}