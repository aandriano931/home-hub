<?php

namespace App\Filament\App\Pages\Perso;


use App\Filament\App\Widgets\Perso\Yearly\YearlyPersoCategoryPieChart;
use App\Filament\App\Widgets\Perso\Yearly\YearlyPersoCategoryRankingBarChart;
use App\Filament\App\Widgets\Perso\Yearly\YearlyPersoParentCategoryPieChart;
use App\Filament\App\Widgets\Perso\Yearly\YearlyPersoParentCategoryRankingBarChart;
use Filament\Pages\Page;

class PersoYearlyAnalyse extends Page
{
    protected static string $view = 'filament.app.pages.perso-yearly-analyse';
    protected static ?string $navigationGroup = 'Perso';
    protected static ?string $navigationLabel = 'Analyse annuelle';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Analyse annuelle du compte perso Fortuneo';
    protected ?string $subheading = 'Analyse des dépenses annuelles sur le compte perso fortuneo depuis juin 2022';

    protected function getHeaderWidgets(): array
    {
        return [
            YearlyPersoParentCategoryPieChart::make(['isInitializedWithPreviousYear' => true]),
            YearlyPersoParentCategoryPieChart::class,
            YearlyPersoCategoryPieChart::make(['isInitializedWithPreviousYear' => true]),
            YearlyPersoCategoryPieChart::class,
            YearlyPersoParentCategoryRankingBarChart::make(['isInitializedWithPreviousYear' => true]),
            YearlyPersoParentCategoryRankingBarChart::class,
            YearlyPersoCategoryRankingBarChart::make(['isInitializedWithPreviousYear' => true]),
            YearlyPersoCategoryRankingBarChart::class,
        ];
    }
}
