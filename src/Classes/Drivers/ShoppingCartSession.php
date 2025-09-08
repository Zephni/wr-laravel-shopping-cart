<?php
namespace WebRegulate\LaravelShoppingCart\Classes\Drivers;

class ShoppingCartSession extends ShoppingCartBase
{
    /**
     * Session container alias, set from handler config
     */
    protected string $sessionPrefix;

    /**
     * Unique identifier for the shopping cart
     */
    protected string $uniqueId;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set session prefix from config
        $this->sessionPrefix = $this->getHandlerConfig()['session_prefix'];

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
        // Store data in session
        session()->put("{$this->sessionPrefix}.cart_data", $this->getShoppingCartDataWithoutModels());

        return true;
    }

    /**
     * Retrieves the data from storage and promises to store in data property
     */
    public function load(): void
    {
        // Load data from session
        $this->shoppingCartData = session()->get("{$this->sessionPrefix}.cart_data", []);
    }
}