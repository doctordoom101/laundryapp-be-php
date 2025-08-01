<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = auth()->user();
        
        if (!in_array($user->role->name, $roles)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}