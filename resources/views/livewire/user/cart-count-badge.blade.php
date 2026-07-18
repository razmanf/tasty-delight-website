<span class="contents">
    <i class="fa-solid fa-cart-shopping mr-1"></i> Cart
    @if($count > 0)
        <span class="ml-1 h-[18px] min-w-[18px] px-1 flex items-center justify-center text-[10px] font-bold rounded-full bg-white text-[#DD6625] leading-none">
            {{ $count > 99 ? '99+' : $count }}
        </span>
    @endif
</span>
