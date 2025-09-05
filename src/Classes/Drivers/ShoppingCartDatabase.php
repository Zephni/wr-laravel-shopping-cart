<?php
namespace WebRegulate\LaravelShoppingCart\Classes\Drivers;

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
        // Store data in database

        return true;
    }

    /**
     * Retrieves the data from storage and promises to store in data property
     * 
     * @return void
     */
    public function load(): void
    {
        // Load data from database
    }
}