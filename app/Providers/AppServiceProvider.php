<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Providers\ParseUserProvider;
use Parse\ParseClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // تهيئة Parse SDK
        ParseClient::initialize(
            env('PARSE_APP_ID'),
            env('PARSE_REST_KEY'),
            env('PARSE_MASTER_KEY')
        );
        ParseClient::setServerURL(env('PARSE_SERVER_URL'), '/');
        
        // تسجيل مزود المصادقة المخصص
        Auth::provider('custom', function ($app, array $config) {
            return new ParseUserProvider();
        });
    }
}
