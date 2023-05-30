<?php

namespace Core;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class MorphServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // If any other models are morphed but not mapped, Laravel
        // will throw a `ClassMorphViolationException` exception.
        Relation::enforceMorphMap([
            'auth.user' => \Core\Auth\Models\User::class,
            'logistic.awb' => \Core\Logistic\Models\Awb::class,
        ]);
    }
}
