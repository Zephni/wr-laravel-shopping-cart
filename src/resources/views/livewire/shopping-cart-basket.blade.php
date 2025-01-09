@props([
    'theme' => 'light', // 'dark', 'light'
])

<div
    class="wr-laravel-shopping-cart shopping-cart-basket relative {{ $theme === 'dark' ? 'text-slate-50 hover:text-white' : '!text-slate-500 hover:!text-slate-600' }}"
    x-data="{ open: false, timer: null }"
    {{-- Check if session of query string has product-added-success --}}
    @if(session('product-added-success') ?? null || request()->query('product-added-success') ?? null == 1)
        x-init="open = true"
    @endif
    @mouseenter="clearTimeout(timer); open = true"
    @mouseleave="timer = setTimeout(() => open = false, 300)"
>
    <div class="group relative group flex items-center gap-2 h-full content-center px-4 transition-colors select-none transition-colors">
        {{-- Icon and cart text --}}
        <i class="shopping-cart-basket-icon fas fa-shopping-cart text-base {{ $theme === 'dark' ? '' : 'group-hover:text-primary-500' }}"></i>
        <span class="text-base font-normal {{ $theme === 'dark' ? '' : 'group-hover:text-primary-500' }}">Cart</span>

        {{-- Count badge --}}
        @if($shoppingCart->getCartItemsCount() > 0)
            <div wire:loading.remove class="absolute flex justify-center items-center top-1/2 transform -translate-x-1/2 -translate-y-1/2 left-[2px] mt-[1px] bg-slate-50 rounded-full border-2 border-aqua-200 font-medium" style="height: 18px;">
                <span class="relative top-[-1.3px] left-[-0px] !text-primary-500 !text-[12px] px-[5px]" style="line-height:  0px;">{{ $shoppingCart->getCartItemsCount() }}</span>
            </div>
        @endif
        {{-- Loading spinner in same position as the count badge --}}
        <div wire:loading class="absolute flex justify-center items-center top-1/2 transform -translate-x-1/2 -translate-y-1/2 left-1">
            <i class="fas fa-spinner fa-spin text-xs text-white"></i>
        </div>
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

            {{-- Clear cart (if not empty) --}}
            @if(count($cartItems) > 0)
                <p
                    wire:click="removeAllCartItems"
                    wire:loading.attr="disabled"
                    class="text-slate-500 hover:text-primary-500 px-2 py-1 text-sm text-right"
                >
                    <i wire:loading.remove wire:target="removeAllCartItems" class="fas fa-trash-alt"></i>
                    <i wire:loading wire:target="removeAllCartItems" class="fas fa-spinner fa-spin"></i>
                    <span>Clear cart</span>
                </p>
            @endif

            <div class="w-full flex flex-col gap-1 overflow-y-auto" style="max-height: 13rem;">
                @forelse($cartItems as $key => $cartItemData)
                    <div class="shopping-cart-basket-product flex justify-between items-center gap-2 px-1 py-1 bg-slate-50 border border-slate-200 rounded-md">
                        <img src="{{ $cartItemData['model']->getCartImage($cartItemData['quantity'], $cartItemData['options']) }}" alt="Product" class="shopping-cart-basket-image w-16 h-16 border border-slate-300 rounded-md" />
                        <div class="w-full text-sm">
                            <p class="font-medium">{!! $cartItemData['model']->getCartName($cartItemData['quantity'], $cartItemData['options']) !!}</p>
                            <div class="text-slate-500">{!! $cartItemData['model']->renderDescription($cartItemData) !!}</div>
                        </div>
                        <button
                            wire:click="removeFromCart('{{ $key }}')"
                            wire:loading.attr="disabled"
                            class="shopping-cart-basket-remove-item-btn text-slate-500 hover:text-primary-500 px-2"
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
            </div>

            <a
                @if(!empty(config('wr-laravel-shopping-cart.checkoutRoute')))
                    @if(!empty($cartItems))
                        href="{{ route('checkout') }}"
                    @endif
                @else
                    onclick="alert('Checkout route (checkoutRoute) must be set in the wr-laravel-shopping-cart config file')"
                @endif
                @if(!empty($cartItems))
                    class="bg-primary-500 hover:bg-primary-600 text-white hover:text-white inline-flex justify-center gap-2 items-center px-3 py-1.5 rounded-md shadow-md"
                @else
                    disabled="disabled"
                    class="bg-slate-500 text-white hover:text-white inline-flex justify-center gap-2 items-center px-3 py-1.5 rounded-md shadow-md select-none"
                    title="Add items to your cart first"
                    style="filter: opacity(0.3)"
                @endif
            >
                <i wire:loading.remove class="fas fa-shopping-cart align-middle"></i>
                <i wire:loading class="fas fa-spinner fa-spin align-middle"></i>
                <span>Checkout</span>
            </a>
            
            {{-- Debug --}}
            {{-- <div class="w-full overflow-x-auto">
                <p>{{ json_encode(config('wr-laravel-shopping-cart.uniqueSessionIdKeyName')) }}</p>
                <p>{{ json_encode(app('WRLaravelShoppingCart')->getShoppingCartDataWithoutModels()) }}</p>
            </div> --}}
        </div>
    </div>
</div>