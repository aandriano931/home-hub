<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function boot()
    {
        // Other boot logic...

        // Conditionally add the 'Perso' navigation group
        if (Gate::allows('view-perso')) {
            NavigationGroup::make()
                ->label('Perso')
                ->icon('heroicon-s-banknotes')
                ->collapsed();
        }
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationItems([
                NavigationItem::make('Administration')
                    ->url('/admin')
                    ->icon('heroicon-s-cog-6-tooth')
                    ->sort(2)
                    ->visible(fn(): bool => auth()->user()->can('view-admin')),
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Budget')
                    ->icon('heroicon-s-banknotes')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Perso')
                    ->icon('heroicon-s-banknotes')
                    ->collapsed(),
            ])
            
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages/*'), for: 'App\\Filament\\App\\Pages\\*')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets/Budget/*'), for: 'App\\Filament\\App\\Widgets\\Budget\\*')
            ->discoverWidgets(in: app_path('Filament/App/Widgets/Perso/*'), for: 'App\\Filament\\App\\Widgets\\Perso\\*')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
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
            ])
            ->spa();
    }
}
