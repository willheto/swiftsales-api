<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        // Intercepts OPTIONS requests
        if ($request->isMethod('OPTIONS')) {
            return response()
                ->json([], 204)
                ->header('Access-Control-Allow-Origin', 'http://localhost:9002')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        // For all other requests, add the CORS headers
        return $next($request)
            ->header('Access-Control-Allow-Origin', 'http://localhost:9002')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization')
            ->header('Access-Control-Allow-Credentials', 'true');
    }
}
