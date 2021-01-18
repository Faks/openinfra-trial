<?php

namespace SolumDeSignum\WebArtisan;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WebArtisanServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../assets' => public_path('solumdesignum/webartisan'),
            ],
            'public'
        );

        Route::middleware('web')
            ->group(
                function () {
                    $this->loadViewsFrom(__DIR__ . '/views', 'webartisan');
                }
            );


        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/../routes.php';
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
