<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBusinessIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    $user = auth()->user();
    if (!$user->is_superadmin && $user->business && !$user->business->is_active) {
        abort(403, 'Your business is suspended. Please renew to continue.');
    }
    return $next($request);
}
}
