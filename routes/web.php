<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

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

if (app()->isProduction()) {
    Livewire::setUpdateRoute(function ($handle) {
        return Route::post(config('livewire.url.update'), $handle);
    });

    Livewire::setScriptRoute(function ($handle) {
        return Route::get(config('livewire.url.js'), $handle);
    });
}

// Route::as('landing.')
//     ->group(function () {
//         Route::get('/', App\Livewire\Landing\Index::class)->name('index');
//     });
Route::get('/', fn () => redirect()->to(Filament::getDefaultPanel()->getPath()))->name('index');
