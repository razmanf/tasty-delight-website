<style>
    /* 1. Admin Header Background & Logo Placement */
    .fi-topbar {
        background-color: #DD6625 !important;
        border-bottom: none !important;
    }
    
    @media (min-width: 1024px) {
        .fi-topbar .fi-logo {
            margin-left: 0rem !important;
        }
    }

    /* Force all select chevrons to maintain correct aspect ratio natively */
    .fi-select-input {
        background-size: 1.25rem 1.25rem !important;
    }

    /* Ensure topbar items are white, but DO NOT affect dropdown panels */
    .fi-topbar > nav > div > div > button,
    .fi-topbar > nav > div > div > a,
    .fi-topbar > nav > div > div .fi-icon-btn-icon,
    .fi-topbar > nav > div > div .fi-topbar-item-label,
    .fi-topbar > nav > div > div .text-gray-500,
    .fi-topbar > nav > div > div .text-gray-400,
    .fi-topbar > nav > div > div .dark\:text-gray-400 {
        color: #ffffff !important;
    }
    
    /* Make the topbar search input look like the user section (transparent/white) */
    .fi-topbar .fi-global-search-field .fi-input-wrp {
        background-color: rgba(255, 255, 255, 0.2) !important;
        border-color: rgba(255, 255, 255, 0.3) !important;
        box-shadow: none !important;
    }
    .fi-topbar .fi-global-search-field .fi-input-wrp input {
        color: #ffffff !important;
    }
    .fi-topbar .fi-global-search-field .fi-input-wrp input::placeholder {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    .fi-topbar .fi-global-search-field .fi-input-wrp svg {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    /* 2. Sidebar Background & Scrollbar */
    
    /* Light Mode */
    .fi-sidebar {
        background-color: #ffffff !important;
        border-right: 1px solid #e5e7eb !important;
    }
    .fi-sidebar .fi-sidebar-item-label, 
    .fi-sidebar .fi-sidebar-item-icon,
    .fi-sidebar .fi-sidebar-group-label {
        color: #4b5563 !important; /* dark gray */
    }
    .fi-sidebar .fi-sidebar-item-active {
        background-color: #fff7ed !important; /* light orange */
    }
    .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-label,
    .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #DD6625 !important; /* primary */
    }
    
    /* Hide Scrollbar (Light & Dark Mode) */
    .fi-sidebar nav {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
    }
    .fi-sidebar nav::-webkit-scrollbar {
        display: none; /* Chrome/Safari/Opera */
    }

    /* Dark Mode */
    .dark .fi-sidebar {
        background-color: #1F2937 !important;
        border-right: none !important;
        box-shadow: 4px 0 15px rgba(0,0,0,0.15) !important;
    }
    .dark .fi-sidebar .fi-sidebar-item-label, 
    .dark .fi-sidebar .fi-sidebar-item-icon,
    .dark .fi-sidebar .fi-sidebar-group-label {
        color: #e5e7eb !important;
    }
    .dark .fi-sidebar .fi-sidebar-item-active {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }
    .dark .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-label,
    .dark .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #ffffff !important;
    }
    




    /* Hide the original native sidebar toggle buttons (hamburger and chevron) everywhere */
    .fi-topbar button[x-on\:click*="sidebar"],
    .fi-sidebar-header-ctn button[x-on\:click*="sidebar"],
    .fi-topbar-collapse-sidebar-btn-ctn {
        display: none !important;
    }

    /* Force Desktop Sidebar Behavior on Mobile */
    @media (max-width: 1024px) {
        /* Force the main content container to have space on the left */
        .fi-main-ctn {
            padding-left: 5.5rem !important;
            transition: padding-left 0.3s ease !important;
        }

        /* If Alpine state says it's open, expand the padding */
        .fi-main-ctn.fi-main-ctn-sidebar-open {
            padding-left: 16rem !important;
        }

        /* Keep sidebar constantly on-screen and below topbar */
        .fi-layout aside.fi-sidebar {
            display: flex !important;
            transform: none !important; 
            translate: 0 !important;
            --tw-translate-x: 0 !important;
            left: 0 !important;
            margin: 0 !important;
            width: 5.5rem !important; /* Collapsed width */
            min-width: 5.5rem !important;
            max-width: 5.5rem !important;
            visibility: visible !important;
            opacity: 1 !important;
            top: 4rem !important; /* Height of the topbar */
            height: calc(100vh - 4rem) !important;
            height: calc(100dvh - 4rem) !important;
            z-index: 2147483647 !important; /* Max z-index */
            background-color: #ffffff !important;
            position: fixed !important;
            border-right: 1px solid rgba(0,0,0,0.05) !important;
        }

        /* When expanded on mobile (using custom toggle) */
        .fi-layout aside.fi-sidebar.fi-sidebar-open {
            width: 16rem !important; /* Expanded width */
            min-width: 16rem !important;
            max-width: 16rem !important;
        }

        /* Hide text labels when collapsed on mobile, but DO NOT hide the logo */
        .fi-layout aside.fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-label,
        .fi-layout aside.fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-label,
        .fi-layout aside.fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-collapse-btn {
            display: none !important;
        }

        /* Hide the native logo inside the sidebar on mobile */
        .fi-layout aside.fi-sidebar .fi-sidebar-header {
            display: none !important;
        }

        /* Force topbar logo container to remain visible on mobile */
        .fi-topbar-start {
            display: flex !important;
        }
        
        /* Hide the dark overlay since it's no longer an off-canvas menu */
        .fi-sidebar-close-overlay {
            display: none !important;
        }
    }

    /* Prevent Sidebar Text Wrapping Glitch during Animation */
    .fi-sidebar nav * {
        white-space: nowrap !important;
    }
    
    .fi-sidebar-group-sub-nav {
        overflow: hidden !important;
    }

    /* Hide native search clear cross mark globally */
    input[type="search"]::-webkit-search-cancel-button {
        -webkit-appearance: none;
        appearance: none;
        display: none !important;
    }

    /* Replace the 'Remove all filters' cross mark with a nice button */
    .fi-ta-filter-indicators .fi-icon-btn:not(.fi-badge-delete-btn) {
        background-color: #EF4444 !important; /* Nice solid red */
        color: white !important;
        border-radius: 0.5rem !important;
        padding: 0.25rem 0.75rem !important; /* Reduced padding */
        width: auto !important;
        height: auto !important;
        transition: all 0.2s ease-in-out !important;
        box-shadow: none !important;
        border: 1px solid transparent !important;
        outline: none !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin-top: -2px !important; /* Pulled UP to perfectly optical center */
    }
    
    .fi-ta-filter-indicators .fi-icon-btn:not(.fi-badge-delete-btn):hover {
        background-color: #EF4444 !important;
        filter: brightness(1.1) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4) !important;
    }

    .fi-ta-filter-indicators .fi-icon-btn:not(.fi-badge-delete-btn) svg {
        display: none !important; /* Hide the X icon */
    }
    
    .fi-ta-filter-indicators .fi-icon-btn:not(.fi-badge-delete-btn)::after {
        content: 'Remove all filters';
        font-size: 0.75rem; /* Reduced text size */
        font-weight: 600;
        white-space: nowrap;
        line-height: 1.25rem; /* Standard tight line height */
    }

    /* Enlarge the individual filter cross for better UX */
    .fi-badge-delete-btn svg {
        width: 1.25rem !important;
        height: 1.25rem !important;
        stroke-width: 2 !important;
    }

    /* ─── Filament Primary Button Override (Match User Dashboard td-btn-primary) ─── */
    .fi-btn.fi-color-primary {
        background-color: #DD6625 !important;
        color: #ffffff !important;
        font-size: 0.875rem !important; /* text-sm */
        font-weight: 600 !important; /* font-semibold */
        border-radius: 0.5rem !important; /* rounded-lg */
        padding: 0.5rem 1rem !important; /* px-4 py-2 */
        text-transform: none !important;
        letter-spacing: normal !important;
        box-shadow: none !important;
        border: 1px solid transparent !important;
        outline: none !important;
        transition: all 0.2s ease-in-out !important;
        opacity: 1 !important;
    }

    /* Base Focus: No outlines, no weird shadows, no dimming */
    .fi-btn.fi-color-primary:focus,
    .fi-btn.fi-color-primary:focus-visible {
        background-color: #DD6625 !important;
        outline: none !important;
        box-shadow: none !important;
        filter: none !important;
        opacity: 1 !important;
    }

    /* Hover State (Mouse over) */
    .fi-btn.fi-color-primary:hover,
    .fi-btn.fi-color-primary:focus:hover {
        background-color: #DD6625 !important;
        filter: brightness(1.1) !important;
        outline: none !important;
        opacity: 1 !important;
    }

    /* Active State (Mouse held down) */
    .fi-btn.fi-color-primary:active {
        filter: brightness(1) !important;
        outline: none !important;
    }

    /* Remove any internal Filament overlay elements or pseudo-elements causing dark backgrounds */
    .fi-btn.fi-color-primary > *[class*="overlay"],
    .fi-btn.fi-color-primary::before,
    .fi-btn.fi-color-primary::after {
        display: none !important;
    }

    /* Fix text inside the button */
    .fi-btn.fi-color-primary .fi-btn-label {
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        color: #ffffff !important;
        text-transform: none !important;
        letter-spacing: normal !important;
        opacity: 1 !important;
    }

    /* ─── Filament Danger Button Override (Match Primary behavior) ─── */
    .fi-btn.fi-color-danger {
        background-color: #EF4444 !important;
        color: #ffffff !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        border-radius: 0.5rem !important;
        padding: 0.5rem 1rem !important;
        text-transform: none !important;
        letter-spacing: normal !important;
        box-shadow: none !important;
        border: 1px solid transparent !important;
        outline: none !important;
        transition: all 0.2s ease-in-out !important;
        opacity: 1 !important;
    }

    /* Base Focus: No outlines, no weird shadows, no dimming */
    .fi-btn.fi-color-danger:focus,
    .fi-btn.fi-color-danger:focus-visible {
        background-color: #EF4444 !important;
        outline: none !important;
        box-shadow: none !important;
        filter: none !important;
        opacity: 1 !important;
    }

    /* Hover State (Mouse over) */
    .fi-btn.fi-color-danger:hover,
    .fi-btn.fi-color-danger:focus:hover {
        background-color: #EF4444 !important;
        filter: brightness(1.1) !important;
        outline: none !important;
        opacity: 1 !important;
    }

    /* Active State (Mouse held down) */
    .fi-btn.fi-color-danger:active {
        filter: brightness(1) !important;
        outline: none !important;
    }

    /* Remove any internal Filament overlay elements */
    .fi-btn.fi-color-danger > *[class*="overlay"],
    .fi-btn.fi-color-danger::before,
    .fi-btn.fi-color-danger::after {
        display: none !important;
    }

    /* Fix text inside the button */
    .fi-btn.fi-color-danger .fi-btn-label {
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        color: #ffffff !important;
        text-transform: none !important;
        letter-spacing: normal !important;
        opacity: 1 !important;
    }
</style>

<script>
document.addEventListener('click', function(event) {
    const button = event.target.closest('button');
    if (button && button.innerText.trim() === 'Apply columns') {
        // Filament dropdowns close on Escape key
        document.dispatchEvent(new KeyboardEvent('keydown', {
            key: 'Escape',
            code: 'Escape',
            keyCode: 27,
            which: 27,
            bubbles: true
        }));
    }
});
</script>

<!-- Prevent Dark Mode Glitch on Livewire Navigation in Admin Panel -->
<script>
    document.addEventListener('livewire:navigated', function () {
        let theme = localStorage.getItem('theme') || 'system';
        if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });
</script>
