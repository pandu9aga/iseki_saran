<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeaderMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('Id_User')) {
            session()->forget('Id_Member');
            return redirect()->route('login')->withErrors(['accessDenied' => 'You must login first']);
        }

        return $next($request);
    }
}

