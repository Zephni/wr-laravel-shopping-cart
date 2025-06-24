<?php

return [
    // If using ShoppingCartSession, this is the key that will be used to store the unique identifier in the session
    'uniqueSessionIdKeyName' => 'wrLaravelShoppingCartUniqueId',

    // Checkout route name
    'checkoutRoute' => null,
    // 'checkoutRoute' => 'checkout',

    // View for shopping cart basket
    'views' => [
        'basket' => 'wr-laravel-shopping-cart::shopping-cart-basket',
    ],
];