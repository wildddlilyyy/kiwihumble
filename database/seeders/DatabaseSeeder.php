<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('ADMIN_NAME', 'KIWI Admin'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'is_admin' => true,
            ],
        );

        $settings = [
            'trip_title' => ['KIWI GROUP Humble Graduation Trip', 'string'],
            'trip_date' => ['2027-05-29', 'date'],
            'timezone' => ['Asia/Taipei', 'timezone'],
        ];

        foreach ($settings as $key => [$value, $type]) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type],
            );
        }
    }
}
