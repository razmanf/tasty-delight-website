<?php

namespace App\Providers\Filament;

use App\Http\Middleware\FilamentAuthenticate as Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(false)
            ->authGuard('web')

            // ─── Branding ────────────────────────────────────────────────
            ->brandName('TastyDelight')
            ->brandLogo(asset('images/tasty-delight-logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('storage/favicons/favicon-96x96.png'))

            // ─── Colors (exact TastyDelight palette) ─────────────────────
            ->colors([
                'primary'   => Color::hex('#DD6625'),
                'secondary' => Color::hex('#FFCD38'),
                'warning'   => Color::hex('#FFB400'),
                'success'   => Color::hex('#22c55e'),
                'danger'    => Color::hex('#ef4444'),
                'info'      => Color::hex('#3b82f6'),
                'gray'      => Color::Slate,
            ])

            // ─── Dark Mode & Layout ───────────────────────────────────────────────
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()

            // ─── Global Search ───────────────────────────────────────────
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])

            // ─── Navigation Groups ───────────────────────────────────────
            ->navigationGroups([
                NavigationGroup::make('Overview'),
                NavigationGroup::make('Orders'),
                NavigationGroup::make('Menu'),
                NavigationGroup::make('People'),
                NavigationGroup::make('Settings'),
            ])

            // ─── Resources & Pages ───────────────────────────────────────
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])

            // ─── Widgets (only admin-specific, no Filament branding widgets)
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])

            // ─── Middleware ──────────────────────────────────────────────
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
