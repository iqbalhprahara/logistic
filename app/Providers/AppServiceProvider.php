<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        if (config('app.force_https') === true) {
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.force_https') === true) {
            URL::forceScheme('https');
        }

        Config::set('livewire.url.update', '/'. \Illuminate\Support\Str::random(random_int(6, 32)));
        Config::set('livewire.url.js', '/'. \Illuminate\Support\Str::random(random_int(6, 32)) .'.js');
    }
}
