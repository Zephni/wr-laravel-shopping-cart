<?php

namespace WebRegulate\LaravelShoppingCart\Classes\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use WebRegulate\LaravelShoppingCart\Classes\ShoppingCartSession;
use Closure;
use WebRegulate\LaravelShoppingCart\Classes\ShoppingCartBase;

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
                $mode = ShoppingCartBase::getMode();
                $class = config("wr-laravel-shopping-cart.handlers.{$mode}.class");
                return new $class();
            });
        });

        return $next($request);
    }
}
