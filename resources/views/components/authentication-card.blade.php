<div class="min-h-screen pt-[450px] max-[470px]:pt-[95vw] sm:pt-[522px] min-[1200px]:pt-0 pb-3 flex flex-col min-[1200px]:justify-center items-center min-[1200px]:px-8">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-xl mt-2 px-6 py-4 bg-white shadow-md sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
