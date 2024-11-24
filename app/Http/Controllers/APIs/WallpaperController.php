<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallpaper;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Helper\Helper;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WallpaperController extends Controller
{
    public function freeWallpapers()
    {
        // Fetch only free wallpapers
        $freeWallpapers = Wallpaper::where('type', 'free')->get();
        $totalFreeWallpapers = $freeWallpapers->count();

        return response()->json(
            [
                'status' => 'success',
                'totalFreeWallpapers' => $totalFreeWallpapers,
                'freeWallpapers' => $freeWallpapers,
            ],
            200,
        );
    }

    public function paidWallpapers()
    {
        // Fetch only free wallpapers
        $paidWallpapers = Wallpaper::where('type', 'paid')->get();
        $totalPaidWallpapers = $paidWallpapers->count();

        return response()->json(
            [
                'status' => 'success',
                'totalPaidWallpapers' => $totalPaidWallpapers,
                'paidWallpapers' => $paidWallpapers,
            ],
            200,
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallpapers = Wallpaper::all();
        $totalWallpapers = $wallpapers->count();
        $freeWallpapersCount = Wallpaper::where('type', 'free')->count();
        $paidWallpapersCount = Wallpaper::where('type', 'paid')->count();

        return response()->json(
            [
                'status' => 'success',
                'totalWallpapers' => $totalWallpapers,
                'freeWallpapersCount' => $freeWallpapersCount,
                'paidWallpapersCount' => $paidWallpapersCount,
                'wallpapers' => $wallpapers,
            ],
            200,
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
                'category' => 'required|string|max:255',
                'type' => 'required|in:free,paid',
                'price' => 'required_if:type,paid|nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => $validator->errors()->first(),
                    ],
                    302,
                );
            }

            // Image Upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Store the image in the public/wallpapers directory
                $path = $image->store('wallpapers', 'public');

                // App URL
                $appurl = 'https://tortoise-new-emu.ngrok-free.app';

                // Generate a full URL to the image
                $imageURL = $appurl . Storage::url($path);
            } else {
                return response()->json(['error' => 'Wallpaper Image upload failed'], 400);
            }

            // Create wallpaper entry
            $wallpaper = Wallpaper::create([
                'image_url' => $imageURL,
                'category' => $request->category,
                'type' => $request->type,
                'price' => $request->type === 'paid' ? $request->price : null,
            ]);

            return response()->json(
                [
                    'message' => 'Wallpaper added successfully',
                    'wallpaper' => $wallpaper,
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                302,
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $wallpaper = Wallpaper::find($id);

        if (!$wallpaper) {
            return response()->json(
                [
                    'message' => 'Wallpaper not found',
                ],
                404,
            );
        }

        return response()->json(
            [
                "status" => "success",
                'wallpaper' => $wallpaper,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
