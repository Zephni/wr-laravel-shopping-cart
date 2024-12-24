<?php
namespace WebRegulate\LaravelShoppingCart\Classes;

abstract class ShoppingCartBase
{
    /**
     * Data 
     * 
     * @var array
     */
    protected array $data;

    /**
     * Saves current data to storage
     * 
     * @param string $uniqueId
     * @return bool True if successful, false otherwise
     */
    abstract public function save(string $uniqueId): bool;

    /**
     * Retrieves the data from storage and promises to store in data property
     * 
     * @param string $uniqueId
     * @return void
     */
    abstract public function load(string $uniqueId): void;
}