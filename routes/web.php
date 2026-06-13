<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
})->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/businesses/{slug?}/edit', function ($slug = 'dakong-balay') {
        return view('admin.businesses.edit', compact('slug'));
    })->name('businesses.edit');
});
