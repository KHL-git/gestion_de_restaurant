<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            /** @var \Illuminate\Contracts\Auth\Authenticatable|null $user */
            $user = auth()->user();
            if ($user && $user->role === 'admin') {
                return $next($request);
            }
        }
        abort(403, 'Accès réservé aux administrateurs.');
    }
}