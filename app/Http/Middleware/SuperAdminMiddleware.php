<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   // App\Http\Middleware\SuperAdminMiddleware.php

public function handle(Request $request, Closure $next): Response
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if (!$user->is_superadmin) {
        abort(403, 'SuperAdmin access only');
    }


    return $next($request);
}


}
