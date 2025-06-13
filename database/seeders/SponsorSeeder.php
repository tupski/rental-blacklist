<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sponsor;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sponsors = [
            [
                'name' => 'RentalPro Indonesia',
                'logo' => 'https://placehold.co/300x150/0066cc/ffffff?text=RentalPro',
                'website_url' => 'https://rentalpro.id',
                'description' => 'Platform manajemen rental terdepan di Indonesia',
                'position' => 'home_top',
                'sort_order' => 1,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
            ],
            [
                'name' => 'AutoRent Solutions',
                'logo' => 'https://placehold.co/300x150/28a745/ffffff?text=AutoRent',
                'website_url' => 'https://autorent.co.id',
                'description' => 'Solusi teknologi untuk bisnis rental kendaraan',
                'position' => 'home_bottom',
                'sort_order' => 1,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => null,
            ],
            [
                'name' => 'MotoGuard Insurance',
                'logo' => 'https://placehold.co/300x150/dc3545/ffffff?text=MotoGuard',
                'website_url' => 'https://motoguard.id',
                'description' => 'Asuransi khusus kendaraan rental',
                'position' => 'footer',
                'sort_order' => 1,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addYear(),
            ],
            [
                'name' => 'FleetTracker',
                'logo' => 'https://placehold.co/300x150/6f42c1/ffffff?text=FleetTracker',
                'website_url' => 'https://fleettracker.id',
                'description' => 'Sistem tracking dan monitoring armada',
                'position' => 'footer',
                'sort_order' => 2,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => null,
            ],
            [
                'name' => 'CarSecure',
                'logo' => 'https://placehold.co/300x150/fd7e14/ffffff?text=CarSecure',
                'website_url' => 'https://carsecure.id',
                'description' => 'Sistem keamanan kendaraan rental',
                'position' => 'footer',
                'sort_order' => 3,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => null,
            ],
        ];

        foreach ($sponsors as $sponsor) {
            Sponsor::create($sponsor);
        }
    }
}
