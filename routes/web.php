<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\ProfileController;

Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::get('profile', ProfileController::class)->name('profile');

    Route::group(['prefix' => 'master-data'], function () {
        Route::resource('user', UserController::class);
    });
});
