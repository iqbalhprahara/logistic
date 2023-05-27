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

Route::get('/', function () {
    return redirect()->to('/login');
});

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
            ->as('administrative.')
            ->group(function () {
                /**
                 * Access
                 */
                Route::prefix('/access')
                    ->as('access.')
                    ->group(function () {
                        Route::get('/role', App\Http\Livewire\CoreSystem\Administrative\Access\Role\Index::class)->name('role');
                        Route::get('/admin', App\Http\Livewire\CoreSystem\Administrative\Access\Admin\Index::class)->name('admin');
                        Route::get('/user', App\Http\Livewire\CoreSystem\Administrative\Access\User\Index::class)->name('user');
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
                Route::get('/company', App\Http\Livewire\CoreSystem\MasterData\Company\Index::class)->name('company');

                /**
                 * Location
                 */
                Route::prefix('/location')
                    ->as('location.')
                    ->group(function () {
                        Route::get('/province', App\Http\Livewire\CoreSystem\MasterData\Location\Province\Index::class)->name('province');
                        Route::get('/city', App\Http\Livewire\CoreSystem\MasterData\Location\City\Index::class)->name('city');
                        Route::get('/subdistrict', App\Http\Livewire\CoreSystem\MasterData\Location\Subdistrict\Index::class)->name('subdistrict');
                    });
            });
    });
