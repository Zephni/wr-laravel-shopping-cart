<?php
namespace WebRegulate\LaravelShoppingCart\Classes;

class ShoppingCartSession extends ShoppingCartBase
{
    /**
     * Session container alias, set from handler config
     */
    public string $sessionContainerAlias;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set session container alias from config
        $this->sessionContainerAlias = $this->getHandlerConfig()['session_container_alias'];

        // Get the unique identifier from session
        $uniqueId = session()->get("{$this->sessionContainerAlias}.unique_id", null);

        // If no unique identifier exists, create one
        if (empty($uniqueId)) {
            $uniqueId = uuid_create();
            session()->put("{$this->sessionContainerAlias}.unique_id", $uniqueId);
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
        session()->put("{$this->sessionContainerAlias}.{$this->uniqueId}", $this->getShoppingCartDataWithoutModels());

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
        $this->shoppingCartData = session()->get("{$this->sessionContainerAlias}.{$this->uniqueId}", []);
    }
}