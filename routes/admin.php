<?php

use Illuminate\Support\Facades\Route;


Route::prefix('/admin/')->name('admin.')->group(function () {

    Route::middleware(['auth:admin'])->group(function () {

        Route::view('dashboard', 'admin.dashboard')->name('dashboard');
    });
    Route::middleware(['guest:admin'])->group(function () {

        Route::view('login', 'admin.auth.login')->name('login');
    });
});
