<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait DataTableTrait
{
   public function getData(Request $request, $model, $searchable = [])
{
    $query = $model::query();

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search, $searchable) {
            foreach ($searchable as $field) {
                $q->orWhere($field, 'like', "%{$search}%");
            }
        });
    }

    return $query->paginate(10);
}
}