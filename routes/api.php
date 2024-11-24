<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Apis\AuthController;
use App\Http\Controllers\Apis\ForgotPasswordController;
use App\Http\Controllers\APIs\WallpaperController;

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

// Wallpapers
Route::post('/add-wallpapers', [WallpaperController::class, 'store']); // Add Wallpapers
Route::get('/wallpapers', [WallpaperController::class, 'index']); // Get All Wallpapers
Route::get('/wallpapers/free', [WallpaperController::class, 'freeWallpapers']); // Get All Free Wallpapers
Route::get('/wallpapers/paid', [WallpaperController::class, 'paidWallpapers']); // Get All Paid Wallpapers
Route::get('/wallpapers/{id}', [WallpaperController::class, 'show']); // Get Wallpapers By ID
