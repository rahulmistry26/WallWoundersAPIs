<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Helper\helper;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class Wallpaper extends Model
{
    use HasFactory;

    protected $fillable = ['image_url', 'category', 'type', 'price'];
}
