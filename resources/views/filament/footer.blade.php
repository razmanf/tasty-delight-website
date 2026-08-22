<footer class="td-admin-footer">
    <div class="td-admin-footer-container">

        <!-- Top Row: Brand + Nav -->
        <div class="td-footer-top">
            <!-- Brand -->
            <div class="td-admin-footer-brand">
                <span class="td-brand-text">TastyDelight</span>
                <span class="td-divider">|</span>
                <span class="td-portal-text">Admin Portal</span>
            </div>

            <!-- Navigation Links -->
            <nav class="td-admin-footer-nav">
                <a href="#">Support</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </nav>
        </div>

        <!-- Bottom Row: Copyright + Version -->
        <div class="td-footer-bottom">
            <div class="td-admin-footer-copy">
                <span>&copy; {{ date('Y') }} TastyDelight by Razman Farook.</span>
                <span class="td-divider">•</span>
                <span class="td-version-text">v1.0.0</span>
            </div>
            <a href="/humans.txt" target="_blank" class="td-unauthorized-link">Unauthorized copying prohibited.</a>
        </div>

    </div>
</footer>

<style>
    /* ─── Footer Base ─── */
    .td-admin-footer {
        width: 100%;
        margin-top: 3rem;
        background-color: #55555F;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
    }

    /* ─── Container ─── */
    /* The footer lives inside Filament's fi-main which already offsets itself from
       the sidebar — so no extra sidebar-compensation padding is needed here. */
    .td-admin-footer-container {
        max-width: 80rem;
        margin: 0 auto;
        padding: 1.5rem 2rem;
    }

    /* ─── Top Row: Brand + Nav ─── */
    .td-footer-top {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 3rem;
        margin-bottom: 1.25rem;
    }

    /* ─── Bottom Row: Copyright + Legal ─── */
    .td-footer-bottom {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.07);
        padding-top: 1rem;
    }

    /* ─── Brand ─── */
    .td-admin-footer-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-shrink: 0;
    }
    .td-brand-text {
        font-size: 1.125rem;
        font-weight: 700;
        letter-spacing: 0.025em;
        color: #ffffff;
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
    }
    .td-divider {
        color: #9ca3af;
    }
    .td-portal-text {
        font-size: 0.875rem;
        font-weight: 500;
        color: #d1d5db;
        white-space: nowrap;
    }

    /* ─── Nav ─── */
    .td-admin-footer-nav {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .td-admin-footer-nav a {
        color: #d1d5db;
        text-decoration: none;
        transition: color 0.2s ease;
        white-space: nowrap;
    }
    .td-admin-footer-nav a:hover {
        color: #ffffff;
    }

    /* ─── Copyright ─── */
    .td-admin-footer-copy {
        font-size: 0.8rem;
        color: #d1d5db;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    .td-version-text {
        font-size: 0.75rem;
        color: #9ca3af;
    }
    .td-unauthorized-link {
        color: #9ca3af;
        font-size: 0.7rem;
        text-decoration: none;
        white-space: nowrap;
        transition: text-decoration 0.2s;
    }
    .td-unauthorized-link:hover {
        text-decoration: underline;
    }

    /* ─── Small screens: stack + center everything ─── */
    @media (max-width: 639px) {
        /* Symmetric padding — no sidebar offset needed (fi-main already handles it) */
        .td-admin-footer-container {
            padding: 1.25rem 1rem;
        }

        /* Stack brand and nav vertically, centered */
        .td-footer-top {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1.25rem;
        }

        /* Brand: vertical stack so the pipe never floats alone */
        .td-admin-footer-brand {
            flex-direction: column;
            align-items: center;
            gap: 0.2rem;
            flex-shrink: 1;
        }
        /* Hide the | pipe — it looks wrong in a vertical stack */
        .td-admin-footer-brand .td-divider {
            display: none;
        }
        .td-brand-text {
            font-size: 1rem;
            white-space: nowrap;
        }
        .td-portal-text {
            font-size: 0.8rem;
            white-space: nowrap;
        }

        /* Nav links: centered, allow natural wrapping */
        .td-admin-footer-nav {
            justify-content: center;
            gap: 0.75rem;
        }
        .td-admin-footer-nav a {
            white-space: nowrap;
        }

        /* Bottom row: stack centered */
        .td-footer-bottom {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.35rem;
        }
        .td-admin-footer-copy {
            justify-content: center;
        }
        .td-unauthorized-link {
            text-align: center;
            white-space: normal; /* override base nowrap — allows text to wrap on tiny screens */
        }
    }
</style>
