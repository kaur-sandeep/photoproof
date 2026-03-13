<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    public function plans()
    {
        // $plans = Plan::where('state', '!=', -1)->get();
        // return response()->json([
        //     'status' => true,
        //     'data' => $plans
        // ]);

        $plans = Plan::where('state', '!=', -1)->first();
        return response()->json([
            'status' => true,
            'data' => $plans
        ]);
    }
}
