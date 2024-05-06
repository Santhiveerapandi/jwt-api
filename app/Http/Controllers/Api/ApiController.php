<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class ApiController extends Controller
{
    //Register Method - POST (name, email, password)
    public function register(Request $request)
    {
        // Validation
        /* $request->validate([
            "name" => "required|string",
            "email" => "required|string|email|unique:users",
            "password" => "required|confirmed" // password_confirmation
        ]); */
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:2,100',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|confirmed|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // User model to save user in database
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        return response()->json([
            "status" => true,
            "message" => "User registered successfully",
            "data" => $user
        ]);
    }

    // Login API - POST (email, password)
    public function login(Request $request){

        // Validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $token = auth()->attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!$token){

            return response()->json([
                "status" => false,
                "message" => "Invalid login details"
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "User logged in succcessfully",
            "token" => $token,
            "expires_in" => auth()->factory()->getTTL() * 60
        ]);

    }

    // Profile API - GET (JWT Auth Token)
    public function profile(){

        //$userData = auth()->user();
        $userData = request()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => $userData,
            //"user_id" => request()->user()->id,
            //"email" => request()->user()->email
        ]);
    }

    // Refresh Token API - GET (JWT Auth Token)
    public function refreshToken(){

        $token = auth()->refresh();

        return response()->json([
            "status" => true,
            "message" => "New access token",
            "token" => $token,
            //"expires_in" => auth()->factory()->getTTL() * 60
        ]);
    }

    // Logout API - GET (JWT Auth Token)
    public function logout(){

        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "User logged out successfully"
        ]);
    }
}
