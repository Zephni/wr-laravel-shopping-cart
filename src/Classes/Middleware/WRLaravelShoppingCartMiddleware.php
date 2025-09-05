<?php

namespace WebRegulate\LaravelShoppingCart\Classes\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use WebRegulate\LaravelShoppingCart\Classes\Drivers\ShoppingCartBase;

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
            return once(function() {
                $driverAlias = ShoppingCartBase::getDriverAlias();
                $class = config("wr-laravel-shopping-cart.drivers.{$driverAlias}.driver");
                return new $class();
            });
        });

        return $next($request);
    }
}
