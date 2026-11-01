<?php

namespace App\Http\Middleware;

use App\Enums\StaffRole;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        $allowedRoles = array_map(
            static fn (string $role): ?string => StaffRole::normalize($role),
            $roles,
        );

        abort_unless(
            $user instanceof User && in_array(StaffRole::normalize($user->role), $allowedRoles, true),
            403,
        );

        return $next($request);
    }
}
