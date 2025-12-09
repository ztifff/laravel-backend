<?php

namespace App\Http\Middleware;

use Closure;

class ForceJsonResponse
{
    public function handle($request, Closure $next)
    {
        // Force JSON Accept Header
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
