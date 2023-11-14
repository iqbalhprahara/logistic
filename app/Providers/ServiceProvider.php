<?php

namespace App\Providers;

use App\Services\AwbService;
use App\Services\ClientService;
use App\Services\ImportService;
use App\Services\SequenceService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(UserService::class);
        $this->app->singleton(ClientService::class);
        $this->app->singleton(AwbService::class);
        $this->app->singleton(ImportService::class);
        $this->app->singleton(SequenceService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
