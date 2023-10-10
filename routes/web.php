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

Route::as('landing.')
    ->group(function () {
        Route::get('/', App\Livewire\Landing\Index::class)->name('index');
    });

Route::middleware('guest')
    ->group(function () {
        Route::get('/login', App\Livewire\CoreSystem\Auth\Login::class)->name('login');
        Route::get('/password/reset', App\Livewire\CoreSystem\Auth\ForgotPassword::class)->name('password.form');
        Route::get('/password/reset/{token}', App\Livewire\CoreSystem\Auth\PasswordReset::class)->name('password.reset');
    });

Route::prefix('/app')
    ->as('app.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', App\Livewire\CoreSystem\Home\Dashboard::class)->name('home');

        /**
         * Administrative
         */
        Route::prefix('/administrative')
            ->as('administrative.')
            ->group(function () {
                /**
                 * Access
                 */
                Route::prefix('/access')
                    ->as('access.')
                    ->group(function () {
                        Route::get('/role', App\Livewire\CoreSystem\Administrative\Access\Role\Index::class)->name('role');
                        Route::get('/admin', App\Livewire\CoreSystem\Administrative\Access\Admin\Index::class)->name('admin');
                        Route::get('/client', App\Livewire\CoreSystem\Administrative\Access\Client\Index::class)->name('user');
                    });
            });

        /**
         * Administrative
         */
        Route::prefix('/master-data')
            ->as('master-data.')
            ->group(function () {
                /**
                 * Company
                 */
                Route::get('/company', App\Livewire\CoreSystem\MasterData\Company\Index::class)->name('company');

                /**
                 * Location
                 */
                Route::prefix('/location')
                    ->as('location.')
                    ->group(function () {
                        Route::get('/province', App\Livewire\CoreSystem\MasterData\Location\Province\Index::class)->name('province');
                        Route::get('/city', App\Livewire\CoreSystem\MasterData\Location\City\Index::class)->name('city');
                        Route::get('/subdistrict', App\Livewire\CoreSystem\MasterData\Location\Subdistrict\Index::class)->name('subdistrict');
                    });

                /**
                 * AWB Status
                 */
                Route::get('/awb-status', App\Livewire\CoreSystem\MasterData\AwbStatus\Index::class)->name('awb-status');
            });

        /**
         * Logistic
         */
        Route::prefix('/logistic')
            ->as('logistic.')
            ->group(function () {
                /**
                 * Pickup
                 */
                Route::prefix('/pickup')
                    ->as('pickup.')
                    ->group(function () {
                        Route::get('/input-pickup', App\Livewire\CoreSystem\Logistic\Pickup\InputPickup\Index::class)->name('input-pickup');
                    });
            });
    });
