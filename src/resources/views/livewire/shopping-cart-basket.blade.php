<div
    x-data="{ open: false, timer: null }"
    @mouseenter="clearTimeout(timer); open = true"
    @mouseleave="timer = setTimeout(() => open = false, 300)"
    class="wr-laravel-shopping-cart shopping-cart-basket relative"
>
    <div class="group relative flex items-center h-full content-center px-4 transition-colors">
        <i class="shopping-cart-basket-icon fas fa-shopping-cart text-xl  text-slate-500 group-hover:text-primary-500"></i>
        @if($shoppingCart->getCartItemsCount() > 0)
            <div class="absolute flex justify-center items-center -bottom-2 left-2 w-5 h-5 text-sm bg-primary-600 text-white rounded-full opacity-80 scale-90">
                <span class="relative top-[-1px]">{{ $shoppingCart->getCartItemsCount() }}</span>
            </div>
        @endif
    </div>

    {{-- Currentcart dropdown --}}
    <div
        x-show="open"
        x-transition
        style="top: calc(100% + 4px);"
        class="shopping-cart-basket-dropdown-menu z-30 absolute right-0 w-72 px-1 py-1 bg-white border border-slate-300 text-slate-600 shadow-lg rounded-md select-none"
    >
        <div class="flex flex-col gap-1">
            {{-- Cart items --}}
            @forelse($shoppingCart->getCartItems() as $cartItemData)
                <div class="shopping-cart-basket-product flex justify-between items-center gap-2 px-1 py-1 bg-slate-100 border border-slate-200 rounded-md">
                    <img src="https://via.placeholder.com/64" alt="Product" class="w-16 h-16 border border-slate-300 rounded-md" />
                    <div class="text-sm">
                        <p>{{ $cartItemData->model->getCartName() }}</p>
                        <p class="text-slate-400">Quantity: {{ $cartItemData->quantity }}</p>
                        <p class="text-slate-400">Price: £{{ $cartItemData->model->getCartPrice() }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center text-slate-400 py-2">
                    <i class="fas fa-shopping-cart text-md pr-2"></i>
                    <span>No items in cart</span>

                    <p>{{ json_encode(config('wr-laravel-shopping-cart.uniqueSessionIdKeyName')) }}</p>
                    <p>{{ json_encode(session()->get('wr-laravel-shopping-cart-'.config('wr-laravel-shopping-cart.uniqueSessionIdKeyName'), [])) }}</p>
                    
                </div>
            @endforelse
        </div>
    </div>
</div>