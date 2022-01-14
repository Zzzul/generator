<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UserController, ProfileController, RoleAndPermissionController};

Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleAndPermissionController::class);

    Route::get('profile', ProfileController::class)->name('profile');

    Route::group(['prefix' => 'master-data'], function () {
    });
});
