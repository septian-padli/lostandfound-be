<?php

namespace App\Providers;

use Google_Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        $this->app->bind(Google_Client::class, function () {
            $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
            return $client;
        });
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
