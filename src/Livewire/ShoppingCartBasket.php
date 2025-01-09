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
     * Theme
     * 
     * @var string
     */
    public string $theme;

    /**
     * @var array Listeners for Livewire events
     */
    public $listeners = [
        'updateShoppingCart' => 'render', // This should be dispatched by anything that updates the shopping cart externally
        'addToCart' => 'addToCart',
        'removeFromCart' => 'removeFromCart',
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
        $model = $modelClass::find($modelId);
        app('WRLaravelShoppingCart')->addCartItem($model, $quantity, $options);
        $this->dispatch('shoppingCartUpdated');
        $this->render();
    }

    /**
     * Remove an item from the shopping cart.
     *
     * @param int $rowIndex The row index of the item to remove.
     * @return void
     */
    public function removeFromCart(int $rowIndex)
    {
        app('WRLaravelShoppingCart')->removeCartItem($rowIndex);
        $this->dispatch('shoppingCartUpdated');
        $this->render();
    }

    /**
     * Clear cart.
     * 
     * @return void
     */
    public function removeAllCartItems()
    {
        app('WRLaravelShoppingCart')->removeAllCartItems();
        $this->dispatch('shoppingCartUpdated');
        $this->render();
    }

    /**
     * Mount the component.
     * 
     * @param string $theme
     * @return void
     */
    public function mount(string $theme = 'light')
    {
        $this->theme = $theme;
    }

    /**
     * Render the shopping cart basket component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('wr-laravel-shopping-cart::livewire.shopping-cart-basket', [
            'shoppingCart' => app('WRLaravelShoppingCart')
        ]);
    }
}