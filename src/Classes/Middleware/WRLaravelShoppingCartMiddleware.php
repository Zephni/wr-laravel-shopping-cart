<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class WRLaravelShoppingCartMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Register singleton for shopping cart
        app()->singleton('WRLaravelShoppingCart', function () {
            // TODO
            return null;
        });

        return $next($request);
    }
}
