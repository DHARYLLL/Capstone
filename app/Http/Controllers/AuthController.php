<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use App\Models\Company;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    // public function showRegister(): View
    // {
    //     $companies = Company::query()
    //         ->with(['businessUnits' => function ($query): void {
    //             $query->orderBy('name');
    //         }])
    //         ->orderBy('name')
    //         ->get();

    //     return view('auth.register', [
    //         'companies' => $companies,
    //     ]);
    // }



    //old
    // public function login(Request $request): RedirectResponse
    // {
    //     $validated = $request->validate([
    //         'email' => ['required', 'string', 'email', 'max:255'],
    //         'password' => ['required', 'string'],
    //         'remember' => ['sometimes', 'boolean'],
    //     ]);

    //     if (! Auth::attempt([
    //         'email' => $validated['email'],
    //         'password' => $validated['password'],
    //     ], $request->boolean('remember'))) {
    //         throw ValidationException::withMessages([
    //             'email' => __('The provided credentials are incorrect.'),
    //         ]);
    //     }

    //     $request->session()->regenerate();

    //     //return redirect()->intended(route('dashboard'));
    //     return redirect()->route('admin.dashboard');
    // }

    //new
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        if (! Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('The provided credentials are incorrect.'),
            ]);
        }

        $request->session()->regenerate();
        $user = Auth::user();
        if ($user instanceof User) {
            $user->update(['last_seen_at' => now()]);
            ActivityLog::record($user->id, 'Logged in', 'Done', null, 'DARIV', 'auth');
        }

        // MAP ROLE TO WHAT protectRoute() EXPECTS IN web.php
        $roleName = match ($user->role) {
            'admin', User::ROLE_ADMIN => 'Administrator',
            'agent', User::ROLE_OPERATOR => 'Lead Operator',
            default                   => $user->role,
        };

        // SET THE EXACT SESSION KEY protectRoute CHECKS FOR
        session([
            'user_name'  => $user->name,
            'user_email' => $user->email,
            'user_role'  => $roleName,
        ]);

        if ($roleName === 'Administrator') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('staff.chat');
    }

    // public function register(Request $request): RedirectResponse
    // {
    //     $validated = $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //         'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_BUSINESS_OWNER, User::ROLE_AGENT])],
    //         'company_id' => ['nullable', 'integer', 'exists:companies,id'],
    //         'business_unit_id' => ['nullable', 'integer', 'exists:business_units,id'],
    //     ]);

    //     $companyId = null;
    //     $businessUnitId = null;

    //     if ($validated['role'] === User::ROLE_ADMIN) {
    //         $companyId = null;
    //         $businessUnitId = null;
    //     } else {
    //         if (! filled($validated['company_id']) || ! filled($validated['business_unit_id'])) {
    //             throw ValidationException::withMessages([
    //                 'business_unit_id' => __('A company and business unit are required for business_owner and agent roles.'),
    //             ]);
    //         }

    //         $companyId = (int) $validated['company_id'];
    //         $businessUnitId = (int) $validated['business_unit_id'];

    //         $matchesCompany = BusinessUnit::query()
    //             ->whereKey($businessUnitId)
    //             ->where('company_id', $companyId)
    //             ->exists();

    //         if (! $matchesCompany) {
    //             throw ValidationException::withMessages([
    //                 'business_unit_id' => __('The selected business unit must belong to the selected company.'),
    //             ]);
    //         }
    //     }

    //     $user = User::query()->create([
    //         'name' => $validated['name'],
    //         'email' => $validated['email'],
    //         'password' => Hash::make($validated['password']),
    //         'role' => $validated['role'],
    //         'company_id' => $companyId,
    //         'business_unit_id' => $businessUnitId,
    //     ]);

    //     event(new Registered($user));

    //     Auth::login($user);

    //     $request->session()->regenerate();

    //     return redirect()
    //         ->route('dashboard')
    //         ->with('status', 'Account created successfully.');
    // }

    public function logout(Request $request): RedirectResponse
    {
        if ($userId = Auth::id()) {
            ActivityLog::record($userId, 'Logged out', 'Done', null, 'DARIV', 'auth');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
