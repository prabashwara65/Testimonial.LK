<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Region;
use App\Models\Country;
use App\Models\Province;
use App\Models\District;
use App\Models\Setting;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (Region::count() === 0) {
            $asia = Region::create([
                'region' => 'Asia'
            ]);

            $europe = Region::create([
                'region' => 'Europe'
            ]);

            $sriLanka = Country::create([
                'region_id' => $asia->id,
                'country' => 'Sri Lanka',
            ]);

            Country::create([
                'region_id' => $europe->id,
                'country' => 'United Kingdom',
            ]);

            $western = Province::create([
                'region_id' => $asia->id,
                'country_id' => $sriLanka->id,
                'province' => 'Western',
            ]);

            Province::create([
                'region_id' => $asia->id,
                'country_id' => $sriLanka->id,
                'province' => 'Central',
            ]);

            District::create([
                'region_id' => $asia->id,
                'country_id' => $sriLanka->id,
                'province_id' => $western->id,
                'district' => 'Colombo',
            ]);

            District::create([
                'region_id' => $asia->id,
                'country_id' => $sriLanka->id,
                'province_id' => $western->id,
                'district' => 'Gampaha',
            ]);
        }

        if (Setting::count() === 0) {
            $settings = [
                ['name' => 'app-name', 'value' => 'Testimonial.LK'],
                ['name' => 'rating-score', 'value' => '5'],
                ['name' => 'star-1', 'value' => 'Poor'],
                ['name' => 'star-2', 'value' => 'Fair'],
                ['name' => 'star-3', 'value' => 'Good'],
                ['name' => 'star-4', 'value' => 'Very Good'],
                ['name' => 'star-5', 'value' => 'Excellent'],
            ];

            foreach ($settings as $setting) {
                Setting::create($setting);
            }
        }

        if (DB::table('question_types')->count() === 0) {
            $now = now();

            foreach ([
                'Text',
                'Single Select',
                'Multi Select',
                'Star Rating',
                'Textarea'
            ] as $type) {
                DB::table('question_types')->insert([
                    'type' => $type,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        if (Admin::count() === 0) {
            $asia = Region::first();

            $country = Country::where(
                'country',
                'Sri Lanka'
            )->first();

            Admin::create([
                'name' => 'Super',
                'last_name' => 'Admin',
                'emp_id' => 'ADM001',
                'username' => 'admin',
                'email' => 'admin@testimonial.lk',
                'password' => Hash::make('password'),
                'nic' => '000000000V',
                'mobile' => '0700000000',
                'region_id' => optional($asia)->id,
                'country_id' => optional($country)->id,
                'status' => 1,
            ]);
        }

        if (DB::table('app_versions')->count() === 0) {
            DB::table('app_versions')->insert([
                'version' => '1.0.0',
                'url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->call(RolesAndPermissionsSeeder::class);
    }
}