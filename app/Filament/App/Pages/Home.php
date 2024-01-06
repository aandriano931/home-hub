<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;

class Home extends Page
{
    protected static ?string $navigationIcon = 'heroicon-m-home';
    protected static string $view = 'filament.app.pages.home';
    protected static ?string $navigationLabel = 'Accueil';
    protected static ?int $navigationSort = -10;
    protected static ?string $title = 'Accueil';

    protected function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class,
        ];
    }
}
