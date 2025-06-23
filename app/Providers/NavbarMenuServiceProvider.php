<?php

namespace App\Providers;

use App\Models\NavbarMenu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class NavbarMenuServiceProvider extends ServiceProvider
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
        // Share navbar menus with all views
        View::composer('*', function ($view) {
            $navbarMenus = NavbarMenu::with('children')
                                   ->main()
                                   ->active()
                                   ->forUser(auth()->user())
                                   ->ordered()
                                   ->get();

            $view->with('navbarMenus', $navbarMenus);
        });
    }
}
