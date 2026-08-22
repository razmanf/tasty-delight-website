<style>
    /* 1. Admin Header Background & Logo Placement */
    .fi-topbar {
        background-color: #DD6625 !important;
        border-bottom: none !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1) !important;
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

    /* Push notifications below the topbar so they don't overlap */
    .fi-no {
        top: 4.5rem !important;
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
        color: rgba(255, 255, 255, 0.5) !important;
    }
    .fi-topbar .fi-global-search-field .fi-input-wrp svg {
        color: rgba(255, 255, 255, 0.5) !important;
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
    
    /* 3. Industry Standard Image Protections */
    img {
        -webkit-user-drag: none !important;
        user-drag: none !important;
        -webkit-user-select: none !important;
        user-select: none !important;
    }
    .fi-topbar img {
        pointer-events: none !important;
    }




    /* Hide the original native sidebar toggle buttons (hamburger and chevron) everywhere */
    .fi-topbar button[x-on\:click*="sidebar"],
    .fi-sidebar-header-ctn button[x-on\:click*="sidebar"],
    .fi-topbar-collapse-sidebar-btn-ctn {
        display: none !important;
    }

    /* Force Desktop Sidebar Behavior on Mobile */
    @media (max-width: 1024px) {
        /* Always reserve collapsed sidebar width in the main content */
        .fi-main-ctn {
            padding-left: 5.5rem !important;
            transition: padding-left 0.3s ease !important;
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
            z-index: 50 !important;
            background-color: #ffffff !important;
            position: fixed !important;
            border-right: 1px solid rgba(0,0,0,0.05) !important;
            transition: width 0.3s ease !important;
        }

        /* When expanded on mobile — overlay mode, do NOT push content */
        .fi-layout aside.fi-sidebar.fi-sidebar-open {
            width: 16rem !important;
            min-width: 16rem !important;
            max-width: 16rem !important;
            z-index: 9999 !important;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.18) !important;
        }

        /* Dark backdrop when sidebar is open on mobile */
        .fi-layout aside.fi-sidebar.fi-sidebar-open::before {
            content: '';
            position: fixed;
            inset: 0;
            left: 16rem;
            background: rgba(0, 0, 0, 0.45);
            z-index: -1;
            pointer-events: auto;
            top: 4rem;
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
        
        /* Hide the default Filament dark overlay since we handle it ourselves */
        .fi-sidebar-close-overlay {
            display: none !important;
        }
    }


    /* Prevent Sidebar Text Wrapping Glitch during Animation */
    .fi-sidebar nav * {
        white-space: nowrap !important;
    }

    /* ─── Fix: All Filament dropdown panels must float ABOVE the sidebar ─── */
    /* Our sidebar is z-index: 50. Filament's default panel z-index is ~20.   */
    /* Without this, the Columns/Filter/etc. dropdowns render behind the sidebar */
    /* on narrow mobile viewports where the dropdown extends leftward.          */
    .fi-dropdown-panel {
        z-index: 60 !important;
    }
    
    /* Base: Style native search clear cross mark globally (Gray for Light Mode) */
    input[type="search"]::-webkit-search-cancel-button {
        -webkit-appearance: none;
        appearance: none;
        height: 16px;
        width: 16px;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(107,114,128,0.7)' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><path d='M4 4 L20 20 M20 4 L4 20'/></svg>");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.2s;
        margin-right: 2px;
    }
    input[type="search"]::-webkit-search-cancel-button:hover {
        opacity: 1;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(107,114,128,1)' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><path d='M4 4 L20 20 M20 4 L4 20'/></svg>");
    }

    /* Dark Mode & Topbar: White cross mark */
    .dark input[type="search"]::-webkit-search-cancel-button,
    .fi-topbar input[type="search"]::-webkit-search-cancel-button {
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(255,255,255,0.7)' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><path d='M4 4 L20 20 M20 4 L4 20'/></svg>");
    }
    .dark input[type="search"]::-webkit-search-cancel-button:hover,
    .fi-topbar input[type="search"]::-webkit-search-cancel-button:hover {
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(255,255,255,1)' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><path d='M4 4 L20 20 M20 4 L4 20'/></svg>");
    }

    /* Hide the entire 'Active filters' row globally since the search query is already in the bar */
    .fi-ta-filter-indicators {
        display: none !important;
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

    /* Fix clipped SVG sparkline strokes in Stats Overview */
    .fi-wi-stats-overview-stat-chart svg {
        overflow: visible !important;
        margin-top: 4px !important;
    }

    /* ─── Chart Widget Section Header: Stack vertically on mobile ─── */
    @media (max-width: 1024px) {
        /* The section header contains heading+description (left) and afterHeader slot (right).
           On narrow screens, force it to stack: heading on top, filter below. */
        .fi-wi-chart .fi-section-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.75rem !important;
        }

        /* Allow the heading block to take full width */
        .fi-wi-chart .fi-section-header-heading-wrapper {
            width: 100% !important;
        }

        /* Allow heading text to wrap naturally — do NOT let it get trapped in a thin column */
        .fi-wi-chart .fi-section-heading {
            white-space: normal !important;
            word-break: break-word !important;
        }

        /* Move filter dropdown to a new row below the heading */
        .fi-wi-chart .fi-section-header-end {
            width: 100% !important;
            display: flex !important;
            justify-content: flex-start !important;
        }

        /* Also fix stats overview cards from overflowing on narrow screens */
        .fi-wi-stats-overview-stat {
            min-width: 0 !important;
        }
    }
    /* ─── Pagination Per-Page Dropdown: match chart widget filter vars ─── */
    .pagination-per-page-vars {
        --ppg-bg: #ffffff;
        --ppg-border: #e5e7eb;
        --ppg-text: #374151;
        --ppg-muted: #9ca3af;
        --ppg-primary: #DD6625;
        --ppg-hover: #f3f4f6;
    }
    .dark .pagination-per-page-vars {
        --ppg-bg: #1f2937;
        --ppg-border: #374151;
        --ppg-text: #e5e7eb;
        --ppg-hover: rgba(55, 65, 81, 0.5);
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
    // 1. Sync Scoped Theme to Global Theme BEFORE Filament boots
    let authId = '{{ auth()->id() }}';
    let scopedTheme = localStorage.getItem('theme_' + authId);
    
    // If the admin has no saved preference, explicitly enforce Light Mode as default
    if (!scopedTheme) {
        scopedTheme = 'light';
        localStorage.setItem('theme_' + authId, 'light');
    }
    
    // Force Filament's global theme to match the Admin's scoped theme
    localStorage.setItem('theme', scopedTheme);

    // 2. Ensure the DOM has the correct class immediately for Livewire Navigation
    document.addEventListener('livewire:navigated', function () {
        let currentTheme = localStorage.getItem('theme_' + authId);
        if (currentTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });

    // 3. Bi-directional Sync: When Filament's toggle changes the class, update the scoped theme!
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                const isDark = document.documentElement.classList.contains('dark');
                const newTheme = isDark ? 'dark' : 'light';
                localStorage.setItem('theme_' + authId, newTheme);
                localStorage.setItem('theme', newTheme);
            }
        });
    });
    
    // Observe the <html> tag for class changes only
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    // 4. Live update the topbar avatar without reloading the page
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('admin-avatar-updated', (data) => {
            // Livewire 3 passes named arguments as an object or array. Safely extract the URL.
            let newUrl = null;
            if (data && data.url) {
                newUrl = data.url;
            } else if (data && data[0] && data[0].url) {
                newUrl = data[0].url;
            }

            if (newUrl) {
                let avatars = document.querySelectorAll('.fi-topbar img');
                avatars.forEach(img => {
                    // Ensure we don't accidentally replace a logo if it's an img
                    if (!img.closest('.fi-logo')) {
                        img.src = newUrl;
                    }
                });
            }
        });
    });
</script>
