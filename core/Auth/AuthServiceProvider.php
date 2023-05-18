<?php

namespace Core\Auth;

use Core\Auth\Commands\MenuCacheReset;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(MenuRegistry $menuRegistry)
    {
        $this->app->singleton(MenuRegistry::class, fn () => $menuRegistry);

        $this->registerCommands();
        $this->registerMigrations();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
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
                MenuCacheReset::class
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

        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(3);
        });
    }

    /**
     * Register the configs.
     *
     * @return void
     */
    private function registerConfigs()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/auth.php', 'core.auth',
        );

        $this->mergeConfigFrom(
            __DIR__.'/config/role.php', 'core.auth-role',
        );

        $this->mergeConfigFrom(
            __DIR__.'/config/menu.php', 'core.auth-menu',
        );

        $this->mergeConfigFrom(
            __DIR__.'/config/permission.php', 'permission',
        );
    }
}
