<div x-data="{ open: false, timer: null }"
    @mouseenter="clearTimeout(timer); open = true"
    @mouseleave="timer = setTimeout(() => open = false, 300)"
>
    <div class="group relative flex items-center h-full content-center px-4 text-slate-400 transition-colors">
        <i class="fas fa-shopping-cart text-xl group-hover:text-primary-500"></i>
        <div class="absolute flex justify-center items-center -bottom-2 left-2 w-5 h-5 text-sm bg-primary-600 text-white rounded-full opacity-80 scale-90">
            <span class="relative top-[-1px]">3</span>
        </div>
    </div>

    <div
        x-show="open"
        x-transition
        class="z-30 absolute right-0 top-full mt-1 w-72 px-2 py-2 bg-white border border-slate-300 text-slate-400 shadow-lg rounded-md"
    >
        {{-- Dummy items for now --}}
        <div class="flex justify-between items-center gap-2">
            <img src="https://via.placeholder.com/64" alt="Product" class="w-16 h-16" />
            <div>
                <h3 class="text-lg font-semibold">Product Name</h3>
                <p class="text-sm text-slate-400">Quantity: 1</p>
            </div>
        </div>
    </div>
</div>