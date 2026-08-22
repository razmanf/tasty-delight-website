<div class="relative" x-data="{ isOpen: false }" @click.outside="if (typeof isOpen !== 'undefined') isOpen = false">
    <button @click="isOpen = !isOpen"
            class="flex items-center gap-2 text-white/80 hover:text-white transition-colors p-1 rounded-full hover:bg-white/15">
        <img src="{{ auth()->user()->profile_photo_url }}"
             draggable="false"
             alt="{{ auth()->user()->name }}"
             class="w-8 h-8 rounded-full object-cover border-2 border-white/30 bg-white/10 select-none pointer-events-none">
        <span class="hidden md:block text-sm font-medium">{{ explode(' ', auth()->user()->name)[0] }}</span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="isOpen ? 'rotate-180' : ''"></i>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="typeof isOpen !== 'undefined' && isOpen"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-56 rounded-xl shadow-xl border overflow-hidden z-50"
         style="background-color: var(--td-bg); border-color: var(--td-border);"
    >
        <div class="px-4 py-3 border-b" style="border-color: var(--td-border);">
            <p class="text-sm font-semibold" style="color: var(--td-text);">{{ auth()->user()->name }}</p>
            <p class="text-xs truncate" style="color: var(--td-muted);">{{ auth()->user()->email }}</p>
        </div>

        <div class="py-1">
            <a href="{{ route('user.settings') }}"
               wire:navigate
               class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors"
               style="color: var(--td-text);">
                <i class="fa-solid fa-gear w-4 text-center" style="color: var(--td-muted);"></i> Settings
            </a>
            <div class="border-t my-1" style="border-color: var(--td-border);"></div>
            <a href="{{ route('about') ?? '#' }}"
               wire:navigate
               class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors"
               style="color: var(--td-text);">
                <i class="fa-solid fa-circle-info w-4 text-center" style="color: var(--td-muted);"></i> About Us
            </a>
            <a href="{{ route('contact') ?? '#' }}"
               wire:navigate
               class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors"
               style="color: var(--td-text);">
                <i class="fa-solid fa-envelope w-4 text-center" style="color: var(--td-muted);"></i> Contact Us
            </a>
            <div class="border-t my-1" style="border-color: var(--td-border);"></div>

            <!-- Dark Mode Toggle -->
            <button @click="dark = !dark"
                    class="flex items-center gap-3 px-4 py-2 text-sm w-full hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors"
                    style="color: var(--td-text);">
                <i class="fa-solid text-gray-400 w-4" :class="dark ? 'fa-moon' : 'fa-sun'"></i>
                <span x-text="dark ? 'Dark Mode' : 'Light Mode'"></span>
                <div class="ml-auto w-8 h-4 rounded-full transition-colors relative" :style="dark ? 'background:#DD6625' : 'background:#D1D5DB'">
                    <div class="absolute top-0.5 w-3 h-3 rounded-full bg-white transition-transform duration-200" :class="dark ? 'translate-x-4' : 'translate-x-0.5'"></div>
                </div>
            </button>
        </div>

        <div class="border-t py-1" style="border-color: var(--td-border);">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 px-4 py-2 text-sm w-full text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <i class="fa-solid fa-right-from-bracket w-4"></i> Sign Out
                </button>
            </form>
        </div>
    </div>
</div>
