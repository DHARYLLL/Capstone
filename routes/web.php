<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
})->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.businesses.index');
    })->name('dashboard');

    Route::get('/businesses/{slug?}/edit', function ($slug = 'dakong-balay') {
        return view('admin.businesses.edit', compact('slug'));
    })->name('businesses.edit');

    Route::get('/businesses', function () {
        return view('admin.businesses.index');
    })->name('businesses.index');

    Route::get('/businesses/knowledge-base', function () {
        return view('admin.businesses.knowledge-base');
    })->name('businesses.knowledge-base');

    Route::get('/businesses/analytics', function () {
        return view('admin.businesses.analytics');
    })->name('businesses.analytics');

    Route::get('/businesses/seo', function () {
        return view('admin.businesses.seo');
    })->name('businesses.seo');

    Route::get('/businesses/chat', function () {
        return view('admin.businesses.chat');
    })->name('businesses.chat');

    Route::get('/businesses/staff', function () {
        return view('admin.businesses.staff');
    })->name('businesses.staff');
});
