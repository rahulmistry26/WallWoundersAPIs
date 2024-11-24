<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Helper\helper;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
        'is_verified',
        'status',
        'role_id',
        'country_code',
        'mobile_number',
        'profile',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function addUser($request)
    {
        $data = new User();
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->user_name = $request->user_name;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->is_verified = '1';
        $data->status = 'Enable';
        $data->role_id = '2';
        $data->country_code = '+91';
        $data->mobile_number = $request->mobile_number;

        /* For Upload Profile pic */
        // $profilepicname = null;
        // if(isset($request->profile) && $request->profile !=''){
        //     $profilelogo   = $request->profile;
        //     $profilepicname = 'Profile-'.$request->first_name.'-'.time().'.'.$request->profile->getClientOriginalExtension();
        //     $profilelogo->move(Helper::profileFileUploadPath(), $profilepicname);
        // }

        if ($request->hasFile('profile')) {
            $image = $request->file('profile');

            // Store the image in the public/profile directory
            $path = $image->store('profile', 'public');

            // App URL
            $appurl = 'https://tortoise-new-emu.ngrok-free.app';

            // Generate a full URL to the image
            $profileURL = $appurl . Storage::url($path);
        } else {
            return response()->json(['error' => 'Profile Image upload failed'], 400);
        }
        $data->profile = $profileURL;
        $data->gender = $request->gender;
        $data->save();

        return $data->toArray();
    }
}
