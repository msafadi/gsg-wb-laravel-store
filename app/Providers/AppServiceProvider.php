<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Str;
use PayPalHttp\Environment;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Cart\CartRepository;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use App\Repositories\Cart\DatabaseRepository;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('cart.cookie_id', function($app) {
            $cookie_id = Cookie::get('cart_id');
            if (!$cookie_id) {
                $cookie_id = Str::uuid();
                Cookie::queue('cart_id', $cookie_id, 24 * 60 * 30);
            }
            return $cookie_id;
        });

        $this->app->bind(CartRepository::class, function($app) {
            return new DatabaseRepository($app->make('cart.cookie_id'));
        });

        $this->app->singleton('paypal.client', function ($app) {
            $clientId = config('services.paypal.client_id');
            $clientSecret = config('services.paypal.client_secret');

            if (config('services.paypal.env') == 'sandbox') {
                $environment = new SandboxEnvironment($clientId, $clientSecret);
            } else {
                $environment = new Environment($clientId, $clientSecret);
            }

            $client = new PayPalHttpClient($environment);
            return $client;
        });

        if ($this->app->environment('production')) {
            $this->app->bind('path.public', function($app) {
                return base_path('public_html');
            });
        }
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
