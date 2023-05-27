<?php

namespace Core\Auth;

use Core\Auth\Commands\MenuCacheReset;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \Spatie\Permission\Models\Role::class => \Core\Auth\Policies\RolePolicy::class,
    ];

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

        Gate::after(function ($user, $ability) {
            return $user->hasRole('Super Admin'); // note this returns boolean
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
                MenuCacheReset::class,
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
        $this->registerPolicies();
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
