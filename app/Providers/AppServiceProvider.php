<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('cart.cookie_id', function() {
            $cookie_id = Cookie::get('cart_id');
            if (!$cookie_id) {
                $cookie_id = Str::uuid();
                Cookie::queue('cart_id', $cookie_id, 24 * 60 * 30);
            }
            return $cookie_id;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Paginator::useBootstrap();
        Paginator::defaultView('pagination.store');
    }
}
