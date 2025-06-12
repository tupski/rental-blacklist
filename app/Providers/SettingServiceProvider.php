<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share settings dengan semua views
        View::composer('*', function ($view) {
            try {
                $globalSettings = [
                    'site_name' => Setting::get('site_name', 'RentalGuard'),
                    'site_tagline' => Setting::get('site_tagline', 'Sistem Blacklist Rental Indonesia'),
                    'contact_email' => Setting::get('contact_email', 'support@rentalguard.id'),
                    'contact_phone' => Setting::get('contact_phone', '+62 21 1234 5678'),
                    'facebook_url' => Setting::get('facebook_url', ''),
                    'twitter_url' => Setting::get('twitter_url', ''),
                    'instagram_url' => Setting::get('instagram_url', ''),
                    'whatsapp_number' => Setting::get('whatsapp_number', ''),
                ];

                $view->with('globalSettings', $globalSettings);
            } catch (\Exception $e) {
                // Jika tabel settings belum ada (saat migration), gunakan default
                $view->with('globalSettings', [
                    'site_name' => 'RentalGuard',
                    'site_tagline' => 'Sistem Blacklist Rental Indonesia',
                    'contact_email' => 'support@rentalguard.id',
                    'contact_phone' => '+62 21 1234 5678',
                    'facebook_url' => '',
                    'twitter_url' => '',
                    'instagram_url' => '',
                    'whatsapp_number' => '',
                ]);
            }
        });
    }
}
