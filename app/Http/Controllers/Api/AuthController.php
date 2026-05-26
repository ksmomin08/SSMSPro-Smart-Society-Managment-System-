<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $request -> validate([
            'email'    => $request -> email,
            'password' => $request -> password,
        ]);

        User::where('email', $request->email)->frist();

        if(!$user || !Hash::check(
            $request->password,
            $user -> password,
        )){
            return response()->json([
                'status' => false,
                'message'=> 'Invalid Credentials'
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => true,

            'token' => $token,

            'user' => $user,
        ]);
    }
}
