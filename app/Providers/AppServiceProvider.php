<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env(key: 'APP_ENV') !=='local') {
            URL::forceScheme(scheme:'https');
          }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Retrieve settings from the database
        $settings = Setting::first(); // You can adjust this to handle scenarios where no settings exist

        // Share settings globally across all views
        View::share('settings', $settings);
        if (env('APP_ENV') !== 'production') {
            URL::forceScheme('http'); // Force HTTP in development
        }

        
    }
}
