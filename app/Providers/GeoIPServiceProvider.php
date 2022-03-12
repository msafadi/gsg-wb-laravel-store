<?php

namespace App\Providers;

use App\Services\IpStack;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class GeoIPServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $ip = '72.229.28.185'; //request()->ip();

        $geoip = new IpStack(config('services.ipstack.key'));
        $response = $geoip->get($ip);

        $locale = $response['location']['languages'][0]['code'] ?? 'en';
        App::setLocale($locale);
    }
}
