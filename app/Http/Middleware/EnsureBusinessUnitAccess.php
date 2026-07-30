<?php

namespace App\Http\Middleware;

use App\Models\BusinessUnit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBusinessUnitAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user, 403);

        if ($user->isAdministrator()) {
            return $next($request);
        }

        $businessUnit = $request->route('businessUnit');

        if ($businessUnit instanceof BusinessUnit) {
            abort_unless((int) $user->business_unit_id === $businessUnit->id, 403);

            return $next($request);
        }

        abort(403);
    }
}
