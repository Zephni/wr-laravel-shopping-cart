<?php

use WebRegulate\LaravelShoppingCart\Classes\Drivers\ShoppingCartDriverSession;
use WebRegulate\LaravelShoppingCart\Classes\Drivers\ShoppingCartDriverDatabase;
use WebRegulate\LaravelShoppingCart\Classes\Models\WrShoppingCart;

return [
    // Drivers configuration
    'drivers' => [
        'session' => [
            'driver' => ShoppingCartDriverSession::class,
            'config' => [
                'session_prefix' => 'wr-shopping-cart',
            ]
        ],
        // Database driver is a work in progress
        // 'database' => [
        //     'driver' => ShoppingCartDatabase::class,
        //     'config' => [
        //         'model' => WrShoppingCart::class,
        //         'unique_id' => fn() => auth()->guest() ? null : auth()?->id(),
        //         'session_prefix' => 'wr-shopping-cart',
        //         'forget_session_on_unique_id' => true,
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
