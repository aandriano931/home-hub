<?php

namespace App\Filament\App\Pages\Perso;

use App\Filament\App\Widgets\Perso\Monthly\MonthlyPersoCategoryPieChart;
use App\Filament\App\Widgets\Perso\Monthly\MonthlyPersoCategoryRankingBarChart;
use App\Filament\App\Widgets\Perso\Monthly\MonthlyPersoParentCategoryPieChart;
use App\Filament\App\Widgets\Perso\Monthly\MonthlyPersoParentCategoryRankingBarChart;
use Filament\Pages\Page;

class PersoMonthlyAnalyse extends Page
{
    protected static string $view = 'filament.app.pages.perso-monthly-analyse';
    protected static ?string $navigationGroup = 'Perso';
    protected static ?string $navigationLabel = 'Analyse mensuelle';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Analyse mensuelle du compte perso Fortuneo';
    protected ?string $subheading = 'Analyse des dépenses mensuelles sur le compte perso fortuneo depuis juin 2022';

    
    protected function getHeaderWidgets(): array
    {
        return [
            MonthlyPersoParentCategoryPieChart::class,
            MonthlyPersoParentCategoryPieChart::make(properties: ['isInitializedWithPreviousMonth' => true]),
            MonthlyPersoCategoryPieChart::class,
            MonthlyPersoCategoryPieChart::make(['isInitializedWithPreviousMonth' => true]),
            MonthlyPersoParentCategoryRankingBarChart::class,
            MonthlyPersoParentCategoryRankingBarChart::make(['isInitializedWithPreviousMonth' => true]),
            MonthlyPersoCategoryRankingBarChart::class,
            MonthlyPersoCategoryRankingBarChart::make(['isInitializedWithPreviousMonth' => true]),
        ];
    }
}
