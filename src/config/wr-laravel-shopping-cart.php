<?php

use WebRegulate\LaravelShoppingCart\Classes\Drivers\ShoppingCartSession;
use WebRegulate\LaravelShoppingCart\Classes\Drivers\ShoppingCartDatabase;
use WebRegulate\LaravelShoppingCart\Classes\Models\WrShoppingCart;

return [
    // Drivers configuration
    'drivers' => [
        'session' => [
            'driver' => ShoppingCartSession::class,
            'config' => [
                'session_prefix' => 'wr-shopping-cart',
            ]
        ],
        // Database driver is a work in progress
        // 'database' => [
        //     'driver' => ShoppingCartDatabase::class,
        //     'config' => [
        //         'model' => WrShoppingCart::class,
        //         'get_cart' => function() {
        //             return WrShoppingCart::getCart(session()->getId(), auth()?->id());
        //         },
        //     ]
        // ],
    ],

    // Current driver
    'driver' => fn() => 'session',

    // Checkout route name
    'checkoutRoute' => null,
    // 'checkoutRoute' => 'checkout',

    // View for shopping cart basket
    'views' => [
        'basket' => 'livewire.wr-laravel-shopping-cart.shopping-cart-basket',
    ],
];
