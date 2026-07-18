<footer class="td-admin-footer">
    <div class="td-admin-footer-container">
        <div class="td-admin-footer-content">
            
            <!-- Brand & Info -->
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
            
            <!-- Copyright & Version -->
            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.25rem;">
                <div class="td-admin-footer-copy">
                    <span>&copy; {{ date('Y') }} TastyDelight by Razman Farook. All rights reserved.</span>
                    <span class="td-divider hidden-mobile">•</span>
                    <span class="td-version-text">v1.0.0</span>
                </div>
                <a href="/humans.txt" target="_blank" style="color: #9ca3af; font-size: 0.7rem; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Unauthorized copying prohibited.</a>
            </div>
            
        </div>
    </div>
</footer>

<style>
    .td-admin-footer {
        width: 100%;
        margin-top: 3rem;
        background-color: #55555F;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
    }
    .td-admin-footer-container {
        max-width: 80rem;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .td-admin-footer-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
    }
    @media (min-width: 768px) {
        .td-admin-footer-content {
            flex-direction: row;
        }
    }
    .td-admin-footer-brand {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .td-brand-text {
        font-size: 1.25rem;
        font-weight: 700;
        letter-spacing: 0.025em;
        color: #ffffff;
        font-family: 'Inter', sans-serif;
    }
    .td-divider {
        color: #9ca3af;
    }
    .td-portal-text {
        font-size: 0.875rem;
        font-weight: 500;
        color: #d1d5db;
    }
    .td-admin-footer-nav {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .td-admin-footer-nav a {
        color: #d1d5db;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .td-admin-footer-nav a:hover {
        color: #ffffff;
    }
    .td-admin-footer-copy {
        font-size: 0.875rem;
        color: #d1d5db;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .td-version-text {
        font-size: 0.75rem;
        color: #9ca3af;
    }
    @media (max-width: 639px) {
        .hidden-mobile {
            display: none;
        }
    }
</style>
