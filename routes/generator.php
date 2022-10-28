<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneratorController;

Route::middleware(['auth', 'web'])->group(function () {
    Route::middleware(['permission:view test'])->get('/tests', function () {
        dd('This is just a test and an example for permission and sidebar menu. You can remove this line on web.php, config/permission.php and config/generator.php');
    });

    Route::middleware('generator-local')->group(function () {
        Route::get("/generators/get-sidebar-menus/{index}", [GeneratorController::class, 'getSidebarMenus'])->name("generators.get-sidebar-menus");
        Route::resource("/generators", GeneratorController::class)->only('create', 'store');
    });
});
