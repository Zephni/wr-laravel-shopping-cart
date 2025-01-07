@props([
    'theme' => 'light', // 'dark', 'light'
])

<div
    class="wr-laravel-shopping-cart shopping-cart-basket relative {{ $theme === 'dark' ? 'text-slate-50 hover:text-white' : '!text-slate-500 hover:!text-slate-600' }}"
    x-data="{ open: false, timer: null }"
    @mouseenter="clearTimeout(timer); open = true"
    @mouseleave="timer = setTimeout(() => open = false, 300)"
>
    <div class="group relative group flex items-center gap-2 h-full content-center px-4 transition-colors select-none transition-colors">
        <i class="shopping-cart-basket-icon fas fa-shopping-cart text-base {{ $theme === 'dark' ? '' : 'group-hover:text-primary-500' }}"></i>
        <span class="text-base font-normal {{ $theme === 'dark' ? '' : 'group-hover:text-primary-500' }}">Cart</span>
        @if($shoppingCart->getCartItemsCount() > 0)
            <div class="absolute flex justify-center items-center -bottom-1 left-1 bg-slate-50 rounded-full border-2 border-emerald-400" style="height: 18px;">
                <span class="relative top-[-1.3px] left-[-0px] !text-primary-500 !text-[12px] px-[5px]" style="line-height:  0px;">{{ $shoppingCart->getCartItemsCount() }}</span>
            </div>
        @endif
    </div>

    {{-- Currentcart dropdown --}}
    <div
        x-show="open"
        x-transition
        style="top: calc(100% + 4px);"
        class="shopping-cart-basket-dropdown-menu z-30 absolute right-0 w-96 px-1 py-1 bg-white border border-slate-300 text-slate-600 shadow-lg rounded-md select-none"
    >
        <div class="flex flex-col gap-1">
            {{-- Cart items --}}
            @php
                $cartItems = $shoppingCart->getCartItems();
            @endphp
            @forelse($cartItems as $key => $cartItemData)
                <div class="shopping-cart-basket-product flex justify-between items-center gap-2 px-1 py-1 bg-slate-50 border border-slate-200 rounded-md">
                    <img src="{{ $cartItemData['model']->getCartImage($cartItemData['quantity'], $cartItemData['options']) }}" alt="Product" class="shopping-cart-basket-image w-16 h-16 border border-slate-300 rounded-md" />
                    <div class="w-full text-sm">
                        <p class="font-medium">{!! $cartItemData['model']->getCartName($cartItemData['quantity'], $cartItemData['options']) !!}</p>
                        <p class="text-slate-500">{!! $cartItemData['model']->renderDescription($cartItemData) !!}</p>
                    </div>
                    <button
                        wire:click="removeFromCart('{{ $key }}')"
                        wire:loading.attr="disabled"
                        class="shopping-cart-basket-remove-item-btn text-slate-400 hover:text-primary-500 px-2"
                    >
                        <i wire:loading.remove wire:target="removeFromCart('{{ $key }}')" class="fas fa-trash"></i>
                        <i wire:loading wire:target="removeFromCart('{{ $key }}')" class="fas fa-spinner fa-spin"></i>
                    </button>
                </div>
            @empty
                <div class="text-center text-slate-600 py-2">
                    <i class="fas fa-shopping-cart text-md pr-2"></i>
                    <span>No items in cart</span>
                </div>
            @endforelse

            <button
                @if(!empty($cartItems))
                    class="bg-primary-500 hover:bg-primary-600 text-white inline-flex justify-center gap-2 items-center text-white px-3 py-1.5 rounded-md shadow-md"
                @else
                    disabled="disabled"
                    class="bg-slate-500 text-white inline-flex justify-center gap-2 items-center text-white px-3 py-1.5 rounded-md shadow-md select-none"
                    title="Add items to your cart first"
                    style="filter: opacity(0.3)"
                @endif
            >
                <i wire:loading.remove class="fas fa-shopping-cart align-middle"></i>
                <i wire:loading class="fas fa-spinner fa-spin align-middle"></i>
                <span>Checkout</span>
            </button>
            
            {{-- Debug --}}
            {{-- <div class="w-full overflow-x-auto">
                <p>{{ json_encode(config('wr-laravel-shopping-cart.uniqueSessionIdKeyName')) }}</p>
                <p>{{ json_encode(app('WRLaravelShoppingCart')->getShoppingCartDataWithoutModels()) }}</p>
            </div> --}}
        </div>
    </div>
</div>