<?php
namespace WebRegulate\LaravelShoppingCart\Classes;

class ShoppingCartSession extends ShoppingCartBase
{
    /**
     * Constructor
     * 
     * @return static
     */
    public function __construct()
    {
        // Get the unique identifier from session
        $uniqueId = session()->get(config('wr-laravel-shopping-cart.uniqueSessionIdKeyName'), null);

        // If no unique identifier exists, create one
        if (empty($uniqueId)) {
            $uniqueId = uuid_create();
            session()->put(config('wr-laravel-shopping-cart.uniqueSessionIdKeyName'), $uniqueId);
        }

        // Call parent constructor
        parent::__construct($uniqueId);
    }

    /**
     * Saves current data to storage
     * 
     * @return bool True if successful, false otherwise
     */
    public function save(): bool
    {
        // Store data in session
        session()->put('wr-laravel-shopping-cart-'.$this->uniqueId, $this->getShoppingCartDataWithoutModels());

        return true;
    }

    /**
     * Retrieves the data from storage and promises to store in data property
     * 
     * @return void
     */
    public function load(): void
    {
        // Load data from session
        $this->shoppingCartData = session()->get('wr-laravel-shopping-cart-'.$this->uniqueId, []);
    }
}