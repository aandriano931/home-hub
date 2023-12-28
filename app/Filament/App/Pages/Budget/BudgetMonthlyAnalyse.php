<?php

namespace App\Filament\App\Pages\Budget;

use App\Filament\App\Widgets\Budget\MonthlyBudgetCategoryPieChart;
use App\Filament\App\Widgets\Budget\MonthlyBudgetCategoryRankingBarChart;
use App\Filament\App\Widgets\Budget\MonthlyBudgetParentCategoryPieChart;
use App\Filament\App\Widgets\Budget\MonthlyBudgetParentCategoryRankingBarChart;
use Filament\Pages\Page;

class BudgetMonthlyAnalyse extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static string $view = 'filament.app.pages.budget-yearly-analyse';
    protected static ?string $navigationGroup = 'Budget';
    protected static ?string $navigationLabel = 'Analyse mensuelle';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Analyse mensuelle du budget';

    protected function getHeaderWidgets(): array
    {
        return [
            MonthlyBudgetParentCategoryPieChart::class,
            MonthlyBudgetCategoryPieChart::class,
            MonthlyBudgetParentCategoryRankingBarChart::class,
            MonthlyBudgetCategoryRankingBarChart::class,
        ];
    }
}
