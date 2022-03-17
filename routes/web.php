<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    GeneratorController,
    UserController,
    ProfileController,
    RoleAndPermissionController
};

Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleAndPermissionController::class);

    Route::get('/profile', ProfileController::class)->name('profile');
    // Route::get('/test', [GeneratorController::class, 'test'])->name('test');

    Route::get('/generators/get-sidebar-menus/{index}', [GeneratorController::class, 'getSidebarMenus'])->name('generators.get-sidebar-menus');
    Route::resource(config('generator.route'), GeneratorController::class)->only('create', 'store');
});
