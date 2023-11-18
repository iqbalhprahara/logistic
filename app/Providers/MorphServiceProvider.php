<?php

namespace App\Providers;

use App\Models;
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
        Relation::morphMap([
            'auth.user' => Models\Auth\User::class,
            'logistic.awb' => Models\Logistic\Awb::class,
        ]);
    }
}
