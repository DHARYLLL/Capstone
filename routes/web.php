<?php

use Illuminate\Support\Facades\Route;

// ── Public: redirects to login page ──
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// ── Embeddable Chat Widget Routes ──
Route::get('/chat/widget', function () {
    $slug = request('business', 'dariv');
    return view('chat.widget', compact('slug'));
})->name('chat.widget');

Route::get('/chat/demo', function () {
    return view('chat.demo');
})->name('chat.demo');

Route::get('/chat/playground', function () {
    return view('chat.playground');
})->name('chat.playground');


// ── Authentication Login / Logout Routes ──
Route::get('/login', function () {
    if (session()->has('user_role')) {
        if (session('user_role') === 'Administrator') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('staff.chat');
        }
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    $email = request('email');
    $password = request('password');

    // Simple mock authentication for demo / evaluation
    if ($email === 'admin@dariv.com' && $password === 'password') {
        session([
            'user_name' => 'Admin User',
            'user_email' => 'admin@dariv.com',
            'user_role' => 'Administrator',
            'user_avatar' => '🛡️'
        ]);
        return redirect()->route('admin.dashboard');
    } elseif ($email === 'mae.s@dariv.com' && $password === 'password') {
        session([
            'user_name' => 'Mae S.',
            'user_email' => 'mae.s@dariv.com',
            'user_role' => 'Lead Operator',
            'user_avatar' => '☔'
        ]);
        return redirect()->route('staff.chat');
    }

    return back()->withErrors(['auth' => 'Invalid email or password. Use "password" for both accounts.']);
})->name('login.post');

Route::get('/logout', function () {
    session()->forget(['user_name', 'user_email', 'user_role', 'user_avatar']);
    return redirect()->route('login');
})->name('logout');


// Helper function to protect routes in a single-company console
if (!function_exists('protectRoute')) {
    function protectRoute(string $viewName, string $requiredRole = null) {
        return function () use ($viewName, $requiredRole) {
            if (!session()->has('user_role')) {
                return redirect()->route('login');
            }

            if ($requiredRole) {
                $userRole = session('user_role');
                if ($requiredRole === 'Administrator' && $userRole !== 'Administrator') {
                    abort(403, 'Unauthorized. This section is restricted to Administrators only.');
                }
                if ($requiredRole === 'Lead Operator' && $userRole !== 'Lead Operator' && $userRole !== 'Administrator') {
                    abort(403, 'Unauthorized. This section is restricted to Operators/Staff only.');
                }
            }

            return view($viewName);
        };
    }
}

// ── Admin panel (Protected Single-Company Console) ──────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', protectRoute('admin.dashboard', 'Administrator'))->name('dashboard');

    Route::get('/knowledge-base', protectRoute('admin.knowledge-base', 'Administrator'))->name('knowledge-base');

    Route::get('/analytics', protectRoute('admin.analytics', 'Administrator'))->name('analytics');

    Route::get('/chat', protectRoute('admin.chat', 'Administrator'))->name('chat');

    Route::get('/staff', protectRoute('admin.staff', 'Administrator'))->name('staff');

    Route::get('/logs', protectRoute('admin.logs', 'Administrator'))->name('logs');

    Route::get('/settings', protectRoute('admin.settings', 'Administrator'))->name('settings');

    Route::post('/settings', function () {
        session([
            'user_name' => request('name'),
            'user_email' => request('email')
        ]);
        return back()->with('success', 'Profile credentials updated successfully!');
    })->name('settings.post');

});

// ── Staff panel (Protected Chat & Handoff Console) ──────────────────────────────────────────────
Route::prefix('staff')->name('staff.')->group(function () {

    Route::get('/chat', protectRoute('staff.chat', 'Lead Operator'))->name('chat');

    Route::get('/settings', protectRoute('staff.settings', 'Lead Operator'))->name('settings');

    Route::post('/settings', function () {
        session([
            'user_name' => request('name'),
            'user_email' => request('email')
        ]);
        return back()->with('success', 'Profile credentials updated successfully!');
    })->name('settings.post');

});


// ── Public fallback: redirect any slug to admin dashboard ──
Route::get('/{slug}', function () {
    return redirect()->route('admin.dashboard');
})->name('business.landing');