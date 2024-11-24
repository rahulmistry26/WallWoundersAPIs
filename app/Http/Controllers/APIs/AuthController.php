<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Helper\Helper;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function loginUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'login' => 'required', // This will be either email or username
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            // Determine if login is email or username
            $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

            // Attempt to log the user in
            $credentials = [
                $login_type => $request->input('login'),
                'password' => $request->input('password')
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->accessToken;

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid login credentials',
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function registerUser(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'user_name' => 'required|unique:users,user_name',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'mobile_number' => 'required|numeric',
                'profile' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
                'gender' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 302);
            }

            // $userData = User::create($request->toarray());
            $userData = User::addUser($request);

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'created successfully.',
                'data' => $userData,
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
            ], 302);
        }
    }
}
