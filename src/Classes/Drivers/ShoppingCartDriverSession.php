<?php
namespace WebRegulate\LaravelShoppingCart\Classes\Drivers;

class ShoppingCartDriverSession extends ShoppingCartDriverBase
{
    /**
     * Session container alias
     */
    protected string $sessionPrefix;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set properties from config
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

    /**
     * Forget session data
     */
    public function forget(): void
    {
        session()->forget("{$this->sessionPrefix}.cart_data");
    }
}