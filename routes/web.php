<?php

use Illuminate\Support\Facades\Route;

// ── Public: platform home (shows a generic landing or redirects to a default tenant) ──
Route::get('/', function () {
    return view('landing.index');
})->name('home');

// ── Public: per-tenant dynamic landing page ────────────────────────────────────────────
// Customers reach this by scanning a QR code or visiting a link like /dakong-balay.
// The view falls back to placeholder defaults until a Business model is wired in.
Route::get('/{slug}', function (string $slug) {
    // TODO: swap the null below for a real model lookup once the Business model exists:
    // $business = \App\Models\Business::where('slug', $slug)->firstOrFail();
    $business = null;
    return view('landing.dynamic', compact('business'));
})->name('business.landing');

// ── Admin panel ────────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.businesses.index');
    })->name('dashboard');

    Route::get('/businesses', function () {
        return view('admin.businesses.index');
    })->name('businesses.index');

    // Static sub-pages MUST come before the {slug} route so they are not swallowed
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

    // Slug-based edit page — must come AFTER all static /businesses/* routes
    Route::get('/businesses/{slug}/edit', function (string $slug) {
        return view('admin.businesses.edit', compact('slug'));
    })->name('businesses.edit');

});

// ── Super-admin panel ──────────────────────────────────────────────────────────────────
Route::prefix('super-admin')->name('super-admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.super-admin.dashboard');
    })->name('dashboard');

    Route::get('/tenants', function () {
        return view('admin.super-admin.tenants');
    })->name('tenants');

    Route::get('/resources', function () {
        return view('admin.super-admin.resources');
    })->name('resources');

    Route::get('/audit-logs', function () {
        return view('admin.super-admin.audit-logs');
    })->name('audit-logs');

});
