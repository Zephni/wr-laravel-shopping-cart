<?php
namespace WebRegulate\LaravelShoppingCart\Classes\Traits;

use Illuminate\Support\Facades\Blade;

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
     * Get the price to display in the cart for this item
     * 
     * @param float $quantity
     * @param ?array $options
     * @return float
     */
    public abstract function getCartPrice(float $quantity, ?array $options = null): float;

    /**
     * Get cart price formatted
     * 
     * @param float $quantity
     * @param array $options
     */
    public function getCartPriceFormatted(float $quantity, array $options): string
    {
        return '£'.number_format($this->getCartPrice($quantity, $options), 2);
    }

    /**
     * Get the image src to display in the cart for this item
     * 
     * @param float $quantity
     * @param array $options
     * @return string
     */
    public function getCartImage(float $quantity, array $options): string
    {
        return 'https://via.placeholder.com/64';
    }

    /**
     * Render description
     * 
     * @param array $cartItemData
     * @return string
     */
    public function renderDescription(array $cartItemData): string
    {
        return Blade::render(<<<BLADE
            Quantity: {{ \$quantity }}<br />
            Price: {{ \$price }}
        BLADE, [
            'quantity' => $cartItemData['quantity'],
            'price' => '£'.$cartItemData['model']->getCartPrice($cartItemData['quantity'], $cartItemData['options']),
        ]);
    }

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
            'model' => $this,
            'modelClass' => get_class($this),
            'modelId' => $this->id,
            'quantity' => $quantity,
            'options' => $options
        ];
    }
}