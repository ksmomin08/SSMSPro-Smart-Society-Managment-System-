<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentApiController extends Controller
{
    public function index()
    {
        $residents = Resident::all();

        return response()->json([

            'status' => true,

            'message' => 'Residents List',

            'data' => $residents

        ]);
    }

    public function show($id)
    {
        $resident = Resident::find($id);

        if(!$resident)
        {
            return response()->json([

                'status' => false,

                'message' => 'Resident Not Found'

            ]);
        }

        return response()->json([

            'status' => true,

            'data' => $resident

        ]);
    }
}
