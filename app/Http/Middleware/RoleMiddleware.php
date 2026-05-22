<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // On suppose que l'utilisateur est déjà authentifié par le middleware 'auth'
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Action non autorisée.');
        }

        return $next($request);
    }
}
