<!-- Sidebar Toggle Button -->
<div class="flex px-4 py-2 mb-2 w-full z-10 relative"
     x-bind:class="$store.sidebar.isOpen ? 'justify-end' : 'justify-center'"
     x-data="{}">
    <button x-on:click="$store.sidebar.isOpen ? $store.sidebar.close() : $store.sidebar.open()" 
            class="text-gray-400 hover:text-primary-500 transition-colors p-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm" 
            title="Toggle Sidebar">
        <!-- Modern Industry Standard Sidebar Collapse SVG Icon -->
        <svg :style="$store.sidebar.isOpen ? 'transform: rotate(0deg);' : 'transform: rotate(180deg);'" 
             style="transition: transform 300ms ease-in-out;"
             xmlns="http://www.w3.org/2000/svg" width="40" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <!-- Rounded Square -->
            <rect x="3" y="3" width="18" height="18" rx="4" ry="4"></rect>
            <!-- Sidebar divider -->
            <line x1="9" y1="3" x2="9" y2="21"></line>
            <!-- Left Chevron -->
            <polyline points="15 16 11 12 15 8"></polyline>
        </svg>
    </button>
</div>
