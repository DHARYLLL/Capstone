<?php

use Illuminate\Support\Facades\Route;

//gi add ni gar -- start --
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperatorChatController;
use App\Http\Controllers\StaffChatController;
use App\Http\Controllers\ChatFeedbackController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminChatController;
//gi add ni gar -- end--

// ── Public: redirects to login page ──
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/chat/demo', function () {
    return view('chat.demo');
})->name('chat.demo');

Route::get('/chat/playground', function () {
    return view('chat.playground');
})->name('chat.playground');


// ── Authentication Login / Logout Routes ──
// Route::get('/login', function () {
//     if (session()->has('user_role')) {
//         if (session('user_role') === 'Administrator') {
//             return redirect()->route('admin.dashboard');
//         } else {
//             return redirect()->route('staff.chat');
//         }
//     }
//     return view('auth.login');
// })->name('login');

// Route::post('/login', function () {
//     $email = request('email');
//     $password = request('password');

//     // Simple mock authentication for demo / evaluation
//     if ($email === 'admin@dariv.com' && $password === 'password') {
//         session([
//             'user_name' => 'Admin User',
//             'user_email' => 'admin@dariv.com',
//             'user_role' => 'Administrator',
//             'user_avatar' => '🛡️'
//         ]);
//         return redirect()->route('admin.dashboard');
//     } elseif ($email === 'mae.s@dariv.com' && $password === 'password') {
//         session([
//             'user_name' => 'Mae S.',
//             'user_email' => 'mae.s@dariv.com',
//             'user_role' => 'Lead Operator',
//             'user_avatar' => '☔'
//         ]);
//         return redirect()->route('staff.chat');
//     }

//     return back()->withErrors(['auth' => 'Invalid email or password. Use "password" for both accounts.']);
// })->name('login.post');

//login in gar
Route::controller(AuthController::class)->group(function (): void {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.attempt');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.store');
});



Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


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

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/knowledge-base', [AdminController::class, 'knowledgeBase'])->name('knowledge-base');
    Route::post('/knowledge-base/chunks', [AdminController::class, 'storeChunk'])->name('knowledge.chunks.store');
    Route::delete('/knowledge-base/chunks/{knowledge}', [AdminController::class, 'destroyChunk'])->name('knowledge.chunks.destroy');
    Route::patch('/knowledge-base/chunks/{knowledge}', [AdminController::class, 'updateChunk'])->name('knowledge.chunks.update');
    Route::post('/knowledge-base/upload', [AdminController::class, 'uploadKnowledge'])
        ->name('knowledge.upload');
    Route::get('/knowledge-base/staged', [AdminController::class, 'stagedKnowledge'])
        ->name('knowledge.staged');
    Route::post('/knowledge-base/staged/{stagedDocument}/approve', [AdminController::class, 'approveStagedKnowledge'])
        ->name('knowledge.approve');
    Route::delete('/knowledge-base/staged/{stagedDocument}', [AdminController::class, 'discardStagedKnowledge'])
        ->name('knowledge.discard');
    Route::post('/business-units/{businessUnit}/knowledge/upload-pdf', [AdminController::class, 'uploadPdf'])
        ->name('knowledge.upload-pdf');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    Route::controller(AdminChatController::class)->group(function (): void {
        Route::get('/chat', 'index')->name('chat');
        Route::get('/chat/sessions', 'sessions')->name('chat.sessions');
        Route::get('/chat/{session}/messages', 'messages')->name('chat.messages');
        Route::post('/chat/{session}/claim', 'claim')->name('chat.claim');
        Route::post('/chat/{session}/reply', 'reply')->name('chat.reply');
        Route::post('/chat/{session}/resolve', 'resolve')->name('chat.resolve');
    });

    Route::get('/staff', [StaffController::class, 'index'])->name('staff');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::patch('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::post('/staff/{id}/restore', [StaffController::class, 'restore'])->name('staff.restore');

    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs');

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
Route::prefix('staff')->name('staff.')->middleware('auth')->group(function () {

    Route::get('/chat', [StaffChatController::class, 'index'])->name('chat');
    Route::get('/chats', [StaffChatController::class, 'sessions'])->name('chats.index');
    Route::get('/chats/{session}/messages', [StaffChatController::class, 'messages'])->name('chats.messages');
    Route::post('/chats/{session}/messages', [StaffChatController::class, 'sendMessage'])->name('chats.messages.store');
    Route::post('/chats/{session}/claim', [StaffChatController::class, 'claimSession'])->name('chats.claim');
    Route::post('/chats/{session}/resolve', [StaffChatController::class, 'resolveSession'])->name('chats.resolve');

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

// para sa chatbot
Route::controller(ChatbotController::class)->group(function (): void {
    // 1. The endpoint that serves the UI inside the iframe
    Route::get('/chat/widget', 'widget')->name('chat.widget');

    // 2. Direct page view (e.g. for standalone testing or full-page view)
    Route::get('/chat/{businessUnit}', 'chat')
        ->whereNumber('businessUnit')
        ->name('chat.show');

    // 3. The API endpoint that receives messages and returns Gemini replies
    Route::post('/chat/{businessUnit}/ask', 'ask')
        ->whereNumber('businessUnit')
        ->name('chat.ask');

    // 4. Public endpoint used by the embedded widget to poll live replies
    Route::get('/get-messages/{sessionId}', 'getMessages')->name('chat.messages');
    Route::get('/api/chatbot/contact-info', [ChatbotController::class, 'getContactInfo'])->name('chatbot.contact-info');
    
    // 5. Cancel handoff endpoint
    Route::post('/api/chatbot/cancel-handoff', [ChatbotController::class, 'cancelHandoff'])->name('chatbot.cancel-handoff');
});

Route::post('/chat/feedback', [ChatFeedbackController::class, 'store'])
    ->name('chat.feedback.store');