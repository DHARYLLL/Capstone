<?php

use Illuminate\Support\Facades\Route;

// ── Public: platform home (shows a generic landing or redirects to a default tenant) ──
Route::get('/', function () {
    return view('landing.index');
})->name('home');



// ── Admin panel ────────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
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

    Route::get('/businesses/logs', function () {
        return view('admin.businesses.logs');
    })->name('businesses.logs');

    // Slug-based edit page — must come AFTER all static /businesses/* routes
    Route::get('/businesses/{slug}/edit', function (string $slug) {
        return view('admin.businesses.edit', compact('slug'));
    })->name('businesses.edit');

    // ── Manager-scoped routes — slug travels with the manager through all pages ──
    // These sit under /admin/businesses/{slug}/... so the sidebar knows which unit is active.
    Route::get('/businesses/{slug}/dashboard', function (string $slug) {
        return view('admin.businesses.manager-dashboard', compact('slug'));
    })->name('businesses.manager-dashboard');

    Route::get('/businesses/{slug}/knowledge-base', function (string $slug) {
        return view('admin.businesses.knowledge-base', compact('slug'));
    })->name('businesses.manager-knowledge-base');

    Route::get('/businesses/{slug}/chat', function (string $slug) {
        return view('admin.businesses.chat', compact('slug'));
    })->name('businesses.manager-chat');

    Route::get('/businesses/{slug}/analytics', function (string $slug) {
        return view('admin.businesses.analytics', compact('slug'));
    })->name('businesses.manager-analytics');

    Route::get('/businesses/{slug}/staff', function (string $slug) {
        return view('admin.businesses.staff', compact('slug'));
    })->name('businesses.manager-staff');

    Route::get('/businesses/{slug}/logs', function (string $slug) {
        return view('admin.businesses.logs', compact('slug'));
    })->name('businesses.manager-logs');

    Route::get('/businesses/{slug}/seo', function (string $slug) {
        return view('admin.businesses.seo', compact('slug'));
    })->name('businesses.manager-seo');

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


// ── Public: per-tenant dynamic landing page ────────────────────────────────────────────
// Customers reach this by scanning a QR code or visiting a link like /dakong-balay.
// The view falls back to placeholder defaults until a Business model is wired in.
Route::get('/{slug}', function (string $slug) {
    // TODO: swap the null below for a real model lookup once the Business model exists:
    // $business = \App\Models\Business::where('slug', $slug)->firstOrFail();
    $business = null;
    return view('landing.dynamic', compact('business'));
})->name('business.landing');