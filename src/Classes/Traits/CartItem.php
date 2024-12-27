<?php
namespace WebRegulate\LaravelShoppingCart\Classes\Traits;

trait CartItem
{
    /**
     * Get the name to display in the cart for this item
     * 
     * @param float $quantity
     * @param array $options
     * @return string
     */
    public abstract function getCartName(float $quantity, array $options): string;

    /**
     * Get the options to display in the cart for this item
     * 
     * @param float $quantity
     * @param array $options
     * @return array
     */
    public abstract function getCartOptions(float $quantity, array $options): string;

    /**
     * Get the price to display in the cart for this item
     * 
     * @param float $quantity
     * @param ?array $options
     * @return float
     */
    public abstract function getCartPrice(float $quantity, ?array $options = null): float;

    /**
     * Build cart item data
     * 
     * @param float $quantity
     * @param array $options
     * @return array
     */
    public function buildCartItemData(float $quantity, array $options): array
    {
        return [
            'modelClass' => get_class($this),
            'modelId' => $this->id,
            'quantity' => $quantity,
            'options' => $options
        ];
    }
}