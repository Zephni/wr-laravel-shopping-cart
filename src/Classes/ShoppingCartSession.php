<?php
namespace WebRegulate\LaravelShoppingCart\Classes;

class ShoppingCartSession extends ShoppingCartBase
{
    /**
     * Saves current data to storage
     * 
     * @param string $uniqueId
     * @return bool True if successful, false otherwise
     */
    public function save(string $uniqueId): bool
    {
        return true;
    }

    /**
     * Retrieves the data from storage and promises to store in data property
     * 
     * @param string $uniqueId
     * @return void
     */
    public function load(string $uniqueId): void
    {
        
    }
}