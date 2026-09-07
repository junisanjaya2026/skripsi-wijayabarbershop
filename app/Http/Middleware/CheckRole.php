<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle($request, Closure $next)
{
    $user = auth()->user();

    // Kalau belum login
    if (!$user) {
        return redirect('/login');
    }

    // Kalau role customer (misal id = 4)
    if ($user->role_id == 4) {
        return redirect('/'); // lempar ke home
    }

    return $next($request);
}
}
