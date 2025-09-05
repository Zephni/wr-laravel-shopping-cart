<?php

return [
    // Handlers configuration
    'handlers' => [
        'session' => [
            'class' => \WebRegulate\LaravelShoppingCart\Classes\ShoppingCartSession::class,
            'config' => [
                'session_container_alias' => 'wr-shopping-cart',
            ]
        ],
    ],    

    // Mode
    'mode' => fn() => 'session',

    // Checkout route name
    'checkoutRoute' => null,
    // 'checkoutRoute' => 'checkout',

    // View for shopping cart basket
    'views' => [
        'basket' => 'livewire.wr-laravel-shopping-cart.shopping-cart-basket',
    ],
];
