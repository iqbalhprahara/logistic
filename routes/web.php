<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')
    ->group(function () {
        Route::get('/login', App\Http\Livewire\CoreSystem\Auth\Login::class)->name('login');
        Route::get('/password/reset', App\Http\Livewire\CoreSystem\Auth\ForgotPassword::class)->name('password.form');
        Route::get('/password/reset/{token}', App\Http\Livewire\CoreSystem\Auth\PasswordReset::class)->name('password.reset');
    });

Route::prefix('/app')
    ->as('app.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', App\Http\Livewire\CoreSystem\Home\Dashboard::class)->name('home');

        /**
         * Administrative
         */
        Route::prefix('/administrative')
            ->as('administrative')
            ->group(function () {
                /**
                 * Access
                 */
                Route::prefix('/access')
                    ->as('access')
                    ->group(function () {
                        Route::get('/role', App\Http\Livewire\CoreSystem\Administrative\Access\Role\Index::class)->name('role');
                    });
            });

        // Route::post('login', [LoginController::class, 'attempt'])->name('login.attempt');

        // Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.form');
        // Route::post('password/reset', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.send-link-email');

        // Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        // Route::post('password/update', [ResetPasswordController::class, 'reset'])->name('password.update');
    });
