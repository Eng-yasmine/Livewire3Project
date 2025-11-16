<?php

use Illuminate\Support\Facades\Route;


Route::prefix('/admin/')->name('admin.')->group(function () {

    Route::middleware(['auth:admin'])->group(function () {

        Route::view('dashboard', 'admin.dashboard')->name('dashboard');

        Route::view('settings', 'admin.settings.index')->name('settings.index');

        Route::view('skills', 'admin.skills.index')->name('skills.index');
    });
    Route::middleware(['guest:admin'])->group(function () {

        Route::view('login', 'admin.auth.login')->name('login');
    });
});
