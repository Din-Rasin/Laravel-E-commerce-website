<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Temporarily allow any authenticated user
        if (!auth()->check()) {
            abort(403, 'Unauthorized action. Please log in.');
        }

        // Log user information for debugging
        \Log::info('User accessing user area', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'user_role' => auth()->user()->role,
            'is_user_method' => auth()->user()->isUser(),
            'path' => $request->path()
        ]);

        return $next($request);
    }
}
