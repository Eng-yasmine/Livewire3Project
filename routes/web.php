<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

/*
 * 
 * front routes
 * 
 */
Route::prefix('/')->name('front.')->group(function (){
//===============home================================
Route::view('home', 'front.index')->name('index');
Route::view('about', 'front.about')->name('about');
Route::view('contact', 'front.contact')->name('contact');
Route::view('service', 'front.service')->name('service');
Route::view('project', 'front.project')->name('project');
Route::view('team', 'front.team')->name('team');
Route::view('testimonial', 'front.testimonial')->name('testimonial');
// Route::view('404', 'front.404')->name('404');
});

require __DIR__.'/auth.php';

require __DIR__.'/admin.php';