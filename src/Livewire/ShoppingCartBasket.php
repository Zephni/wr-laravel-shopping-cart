<?php
namespace WebRegulate\LaravelShoppingCart\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ShoppingCartBasket
 * @package WebRegulate\LaravelShoppingCart
 */
class ShoppingCartBasket extends Component
{
    /**
     * @var array Listeners for Livewire events
     */
    public $listeners = [
        'shoppingCartUpdated' => 'render',
        'addToCart' => 'addToCart',
    ];

    /**
     * Add an item to the shopping cart.
     *
     * @param string $modelClass The full model class
     * @param int $modelId The ID of the model to add to the cart.
     * @param float $quantity The quantity of the item to add.
     * @param array $options Additional options for the item.
     * @return void
     */
    public function addToCart(string $modelClass, int $modelId, float $quantity = 1, array $options = [])
    {
        $shoppingCart = app('WRLaravelShoppingCart');
        $model = $modelClass::find($modelId);
        $shoppingCart->addCartItem($model, $quantity, $options);
        $this->render();
    }

    /**
     * Render the shopping cart basket component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $shoppingCart = app('WRLaravelShoppingCart');

        return view('wr-laravel-shopping-cart::livewire.shopping-cart-basket', [
            'shoppingCart' => $shoppingCart
        ]);
    }
}