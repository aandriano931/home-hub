<?php

namespace App\Filament\App\Pages\Perso;

use App\Filament\App\Widgets\Perso\Macro\MacroPersoCategoryLineChart;
use App\Filament\App\Widgets\Perso\Macro\MacroPersoCategoryPieChart;
use App\Filament\App\Widgets\Perso\Macro\MacroPersoCategoryRankingBarChart;
use App\Filament\App\Widgets\Perso\Macro\MacroPersoGlobalLineChart;
use App\Filament\App\Widgets\Perso\Macro\MacroPersoParentCategoryLineChart;
use App\Filament\App\Widgets\Perso\Macro\MacroPersoParentCategoryPieChart;
use App\Filament\App\Widgets\Perso\Macro\MacroPersoParentCategoryRankingBarChart;
use Filament\Pages\Page;

class PersoMacroAnalyse extends Page
{
    protected static string $view = 'filament.app.pages.perso-macro-analyse';
    protected static ?string $navigationGroup = 'Perso';
    protected static ?string $navigationLabel = 'Analyse macro';
    protected static ?int $navigationSort = 4;
    protected static ?string $title = 'Analyse macro du compte perso Fortuneo';
    protected ?string $subheading = 'Analyse de l\'ensemble des dépenses sur le compte perso fortuneo depuis juin 2022';
    
    protected function getHeaderWidgets(): array
    {
        return [
            MacroPersoGlobalLineChart::class,
            MacroPersoParentCategoryRankingBarChart::class,
            MacroPersoCategoryRankingBarChart::class,
            MacroPersoParentCategoryPieChart::class,
            MacroPersoCategoryPieChart::class,
            MacroPersoParentCategoryLineChart::class,
            MacroPersoCategoryLineChart::class,
        ];
    }
}
