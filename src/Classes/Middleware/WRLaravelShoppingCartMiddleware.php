<?php

namespace WebRegulate\LaravelShoppingCart\Classes\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use WebRegulate\LaravelShoppingCart\Classes\ShoppingCartSession;
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
            // TODO, we need a way of defining these in config and potentially hot switching them based on conditions.
            //   For example, switching from session to database mode if user logs in / out. Note that the data would need to be transferred between the two.
            // Note that shopping cart session will automatically create a unique identifier for the session if it doesn't exist, it
            //   will also load the data from the session if it exists
            return new ShoppingCartSession();
        });

        return $next($request);
    }
}
