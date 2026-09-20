<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $this->authorizeAdmin();

        $staffMembers = User::withTrashed()
            ->with(['chatSessions' => fn ($query) => $query->whereIn('status', ['handed_off', 'human_active'])])
            ->orderBy('name')
            ->get()
            ->map(fn (User $user): array => [
                'user' => $user,
                'status' => $user->currentStatus(),
            ]);

        return view('admin.staff', compact('staffMembers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, 'Staff', User::ROLE_OPERATOR])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'company_id' => optional($request->user())->company_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Staff member added successfully.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->authorizeAdmin();

        $user = User::withTrashed()->findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in($this->staffRoles())],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        if (filled($validated['password'] ?? null)) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Staff member updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->authorizeAdmin();
        User::findOrFail($id)->delete();

        return back()->with('success', 'Staff member terminated successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $this->authorizeAdmin();
        User::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Staff member restored successfully.');
    }

    private function authorizeAdmin(): void
    {
        if (! session()->has('user_role')) {
            redirect()->route('login')->send();
        }

        abort_unless(session('user_role') === 'Administrator', 403, 'Unauthorized. This section is restricted to Administrators only.');
    }

    private function staffRoles(): array
    {
        return [User::ROLE_ADMIN, 'Staff', User::ROLE_OPERATOR, 'agent'];
    }
}