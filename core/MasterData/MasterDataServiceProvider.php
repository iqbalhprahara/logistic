<?php

namespace Core\MasterData;

use Illuminate\Support\ServiceProvider;

class MasterDataServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
        $this->registerMigrations();
    }

    /**
     * Register the migrations.
     *
     * @return void
     */
    private function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        }
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                //
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfigs();
    }

    /**
     * Register the configs.
     *
     * @return void
     */
    private function registerConfigs()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/sequence.php', 'sequence',
        );
    }
}
