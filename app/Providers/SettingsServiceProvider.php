<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
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
        
        // Get settings from the cache
        $settings = Cache::get(Setting::CACHE_KEY);
        if (!$settings) {
            $settings = Setting::pluck('value', 'name');
            Cache::put(Setting::CACHE_KEY, $settings);
        }

        foreach ($settings as $key => $value) {
            Config::set($key, $value);
        }
    }
}
