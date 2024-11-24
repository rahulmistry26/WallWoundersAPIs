<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();

        // Admin
        User::create([
            'first_name'        => config('const.admin.first_name'),
            'last_name'         => config('const.admin.last_name'),
            'user_name'         => config('const.admin.user_name'),
            'email'             => config('const.admin.email'),
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make(config('const.admin.password')),
            'is_verified'       => config('const.admin.is_verified'),
            'status'            => config('const.admin.status'),
            'role_id'           => config('const.admin.role_id'),
            'country_code'      => config('const.admin.country_code'),
            'mobile_number'     => config('const.admin.mobile_number'),
            'profile'           => config('const.admin.profile'),
            'gender'            => config('const.admin.gender'),
        ]);
    }
}
