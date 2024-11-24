<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Helper\Helper;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function checkEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => $validator->errors()->first(),
                    ],
                    400,
                );
            }

            // Check if email is exist or not
            $user = User::where('email', $request->email)->first();

            if ($user) {
                // Generate a token for password reset
                $token = Str::random(60);

                // Check if email exists in the password_resets table
                $existingReset = DB::table('password_resets')
                    ->where('email', $request->email)
                    ->first();

                if ($existingReset) {
                    // Email exists, delete the old record
                    DB::table('password_resets')
                        ->where('email', $request->email)
                        ->delete();
                }

                // Store the email and token in the password_resets table
                DB::table('password_resets')->updateOrInsert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => now(),
                ]);

                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Email exists in database',
                        'token' => $token,
                    ],
                    200,
                );
            } else {
                // Email does not exist
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'email does not exist in database',
                    ],
                    404,
                );
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $th->getMessage(),
                ],
                500,
            );
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            // Validate the request inputs
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => $validator->errors()->first(),
                    ],
                    400,
                );
            }

            // Check if the email and token exist in the password_resets table
            $passwordReset = DB::table('password_resets')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            if (!$passwordReset) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid token or email',
                ], 400);
            }

            // Find the user exist with email or not
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ], 404);
            }

            // Update User's Password
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete the password reset entry from the password_resets table
            DB::table('password_resets')->where('email', $request->email)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Password updated successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 302);
        }
    }
}
