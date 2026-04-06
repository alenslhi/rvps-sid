<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FirstLoginMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->is_first_login && ! $request->routeIs('first-login.*') && ! $request->routeIs('logout')) {
            return redirect()->route('first-login.edit');
        }

        return $next($request);
    }
}