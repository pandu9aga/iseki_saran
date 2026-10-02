<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InternalSecretMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('app.key');
        $provided = $request->header('X-Internal-Secret')
            ?? $request->input('_internal_secret');

        if (!$provided || !hash_equals($expected, $provided)) {
            abort(403, 'Forbidden: internal endpoint');
        }

        return $next($request);
    }
}
