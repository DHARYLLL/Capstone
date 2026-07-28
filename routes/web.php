<?php

use Illuminate\Support\Facades\Route;

// ── Public: platform home (redirects to admin dashboard) ──
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
})->name('home');

// ── Embeddable Chat Widget Routes ──
Route::get('/chat/widget', function () {
    $slug = request('business', 'aquashield');
    return view('chat.widget', compact('slug'));
})->name('chat.widget');

Route::get('/chat/demo', function () {
    return view('chat.demo');
})->name('chat.demo');


// ── Admin panel (Single-Company Console) ──────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/knowledge-base', function () {
        return view('admin.knowledge-base');
    })->name('knowledge-base');

    Route::get('/analytics', function () {
        return view('admin.analytics');
    })->name('analytics');

    Route::get('/chat', function () {
        return view('admin.chat');
    })->name('chat');

    Route::get('/staff', function () {
        return view('admin.staff');
    })->name('staff');

    Route::get('/logs', function () {
        return view('admin.logs');
    })->name('logs');

    Route::get('/services', function () {
        return view('admin.products');
    })->name('products');

});


// ── Public fallback: redirect any slug to admin dashboard ──
Route::get('/{slug}', function () {
    return redirect()->route('admin.dashboard');
})->name('business.landing');