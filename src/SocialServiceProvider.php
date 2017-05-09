<?php

namespace Laralum\Shop;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class SocialServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/Translations', 'laralum_social');

        if (!$this->app->routesAreCached()) {
            require __DIR__.'/Routes/web.php';
        }

        $this->loadViewsFrom(__DIR__.'/Views', 'laralum_social');

        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->app->register('Laravel\\Socialite\\SocialiteServiceProvider');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
