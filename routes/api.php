<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Apis\AuthController;
use App\Http\Controllers\Apis\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Register User
Route::post('/register', [AuthController::class, 'registerUser']);

// Login User
Route::post('/login', [AuthController::class, 'loginUser']);

// Check Email for Forgot Password
Route::post('/check_email', [ForgotPasswordController::class, 'checkEmail']);

// Reset Password
Route::post('/reset_password', [ForgotPasswordController::class, 'resetPassword']);
