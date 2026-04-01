<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (auth()->user()->hasAnyRole($roles)) {
            return $next($request);
        }

        // If request wants JSON, return JSON error
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Otherwise render 403 error view if it exists
        if (view()->exists('errors/403')) {
            return response()->view('errors/403', [], 403);
        }

        // Fallback to abort if view doesn't exist
        abort(403, 'Unauthorized');
    }
}
