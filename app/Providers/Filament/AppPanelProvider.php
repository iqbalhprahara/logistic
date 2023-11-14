<?php

namespace App\Providers\Filament;

use App\Filament\AppPanel\Pages\Auth\Login;
use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Filament\Actions\Action;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Forms\Components\Textarea;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Vite;
use Illuminate\Validation\ValidationException;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin;

class AppPanelProvider extends PanelProvider
{
    private const PATH = 'app';

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->bootUsing(function (Panel $panel) {
                Page::$reportValidationErrorUsing = function (ValidationException $exception) {
                    Notification::make()
                        ->title($exception->getMessage())
                        ->danger()
                        ->send();
                };

                Table::configureUsing(function (Table $table): void {
                    $table->deferLoading()
                        ->paginationPageOptions([5, 10, 25])
                        ->persistColumnSearchesInSession(false)
                        ->persistFiltersInSession(false)
                        ->persistSearchInSession(false)
                        ->persistSortInSession(false);
                });

                Tables\Grouping\Group::configureUsing(function (Tables\Grouping\Group $group) {
                    $group->titlePrefixedWithLabel(false)
                        ->collapsible();
                });

                Action::configureUsing(function (Action $action) {
                    $action->closeModalByClickingAway(false);
                });

                TextColumn::configureUsing(function (TextColumn $column) {
                    $column->size(TextColumnSize::ExtraSmall);
                });

                Textarea::configureUsing(function (Textarea $component) {
                    $component->autosize();
                });
            })
            ->default()
            ->maxContentWidth('full')
            ->id('app')
            ->path(self::PATH)
            ->colors([
                'primary' => '#246c84',
                'accent' => '#af4d0e',
            ])
            ->font('Inter', provider: GoogleFontProvider::class)
            ->viteTheme('resources/css/filament/app/theme.css')
            ->brandLogo(Vite::asset('resources/images/logo/color.svg'))
            ->darkModeBrandLogo(Vite::asset('resources/images/logo/color-no-bg.svg'))
            ->brandLogoHeight('2.5rem')
            // ->spa()
            ->login(Login::class)
            ->passwordReset()
            ->profile()
            ->sidebarCollapsibleOnDesktop()
            ->plugins([
                new \RickDBCN\FilamentEmail\FilamentEmail(),
                LightSwitchPlugin::make()->position(Alignment::BottomCenter),
                FilamentProgressbarPlugin::make()->color('#246c84'),
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups($this->buildNavigations(config('menu.items.app_panel', [])));
            })
            ->discoverResources(in: app_path('Filament/AppPanel/Resources'), for: 'App\\Filament\\AppPanel\\Resources')
            ->discoverPages(in: app_path('Filament/AppPanel/Pages'), for: 'App\\Filament\\AppPanel\\Pages')
            ->pages([
                //
            ])
            ->discoverWidgets(in: app_path('Filament/AppPanelWidgets/'), for: 'App\\Filament\\AppPanel\\Widgets')
            ->widgets([

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
            ]);
    }

    private function buildNavigations(array $menus): array
    {
        $nav = [];

        foreach ($menus as $menu) {
            $nav = array_merge($nav, match (optional($menu)['type']) {
                'group' => $this->buildGroupNavigationItem($menu),
                'resource' => $this->buildResourceNavigationItem($menu),
                'page' => $this->buildPageNavigationItem($menu),
                'url' => $this->buildUrlNavigationItem($menu),
                default => [],
            });
        }

        return $nav;
    }

    private function buildGroupNavigationItem(array $menu)
    {
        $navItems = $this->buildNavigations($menu['submenus']);

        if (empty($navItems)) {
            return [];
        }

        $collapsible = true;
        if (isset($menu['collapsible'])) {
            $collapsible = boolval($menu['collapsible']);
        }

        return [
            NavigationGroup::make()
                ->label($menu['name'])
                ->icon($menu['icon'] ?? null)
                ->items($navItems)
                ->collapsible($collapsible),
        ];
    }

    private function buildResourceNavigationItem(array $menu)
    {
        if (! isset($menu['gate']) || auth()->user()->can($menu['gate'])) {
            $resourceClass = $menu['resource'];

            return [$resourceClass::getNavigationItems()[0]->label($menu['name'])];
        }

        return [];
    }

    private function buildPageNavigationItem(array $menu)
    {
        if (! isset($menu['gate']) || auth()->user()->can($menu['gate'])) {
            $pageClass = $menu['page'];

            return [$pageClass::getNavigationItems()[0]->label($menu['name'])];
        }

        return [];
    }

    private function buildUrlNavigationItem(array $menu)
    {
        if (! isset($menu['gate']) || auth()->user()->can($menu['gate'])) {
            return [
                NavigationItem::make($menu['name'])
                    ->icon($menu['icon'] ?? 'heroicon-m-arrow-up-right')
                    ->url($menu['url'] ?? route($menu['route_name']) ?? 'index'),
            ];
        }

        return [];
    }
}
