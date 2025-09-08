<?php
namespace WebRegulate\LaravelShoppingCart\Classes\Drivers;

use Exception;

class ShoppingCartDatabase extends ShoppingCartBase
{
    /**
     * Model class, set from handler config
     */
    public string $modelClass;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set model class from config
        $this->modelClass = $this->getHandlerConfig()['model'];

        // Call parent constructor
        parent::__construct();
    }

    /**
     * Saves current data to storage
     * 
     * @return bool True if successful, false otherwise
     */
    public function save(): bool
    {
        // Get cart either from new / existing record
        $shoppingCartInstance = $this->getCart();

        // Update cart_data
        $shoppingCartInstance->update([
            'cart_data' => json_encode($this->shoppingCartData),
        ]);

        return true;
    }

    /**
     * Retrieves the data from storage and promises to store in data property
     * 
     * @return void
     */
    public function load(): void
    {
        // Get cart either from new / existing record
        $shoppingCartInstance = $this->getCart();

        // Check model is an instance of $this->modelClass
        if (!($shoppingCartInstance instanceof $this->modelClass)) {
            // Throw error
            throw new Exception('Shopping cart model returned from wr-laravel-shoping-cart.get_cart config is not an instance of: ' . $this->modelClass);
        }

        if ($shoppingCartInstance) {
            $this->shoppingCartData = json_decode($shoppingCartInstance->cart_data, true) ?? [];
        } else {
            $this->shoppingCartData = [];
        }
    }

    /**
     * Get cart
     */
    public function getCart()
    {
        $getCartClosure = $this->getHandlerConfig()['get_cart'];
        return call_user_func($getCartClosure);
    }
}