<?php
namespace WebRegulate\LaravelShoppingCart\Livewire;

use Livewire\Component;

class ShoppingCartBasket extends Component
{
    public function mount()
    {
        
    }

    public function render()
    {
        $shoppingCart = app('WRLaravelShoppingCart');

        return view('wr-laravel-shopping-cart::livewire.shopping-cart-basket', [
            'shoppingCart' => $shoppingCart
        ]);
    }
}