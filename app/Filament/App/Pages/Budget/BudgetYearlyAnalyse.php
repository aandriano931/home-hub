<?php

namespace App\Filament\App\Pages\Budget;

use App\Filament\App\Widgets\Budget\YearlyBudgetCategoryPieChart;
use App\Filament\App\Widgets\Budget\YearlyBudgetCategoryRankingBarChart;
use App\Filament\App\Widgets\Budget\YearlyBudgetParentCategoryPieChart;
use App\Filament\App\Widgets\Budget\YearlyBudgetParentCategoryRankingBarChart;
use Filament\Pages\Page;

class BudgetYearlyAnalyse extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static string $view = 'filament.app.pages.budget-yearly-analyse';
    protected static ?string $navigationGroup = 'Budget';
    protected static ?string $navigationLabel = 'Analyse annuelle';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Analyse annuelle du budget depuis 2020';

    protected function getHeaderWidgets(): array
    {
        return [
            YearlyBudgetParentCategoryPieChart::class,
            YearlyBudgetCategoryPieChart::class,
            YearlyBudgetParentCategoryRankingBarChart::class,
            YearlyBudgetCategoryRankingBarChart::class,
        ];
    }
}
