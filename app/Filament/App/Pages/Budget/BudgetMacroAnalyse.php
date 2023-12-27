<?php

namespace App\Filament\App\Pages\Budget;

use App\Filament\App\Widgets\Budget\TotalBudgetCategoryRankingBarChart;
use App\Filament\App\Widgets\Budget\TotalBudgetMonthlySpendingsLineChart;
use App\Filament\App\Widgets\Budget\TotalBudgetMonthlyIncomesLineChart;
use App\Filament\App\Widgets\Budget\TotalBudgetParentCategoryRankingBarChart;
use Filament\Pages\Page;

class BudgetMacroAnalyse extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static string $view = 'filament.app.pages.budget-macro-analyse';
    protected static ?string $navigationGroup = 'Budget';
    protected static ?string $navigationLabel = 'Analyse macro';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Analyse macro des données du budget';

    protected function getHeaderWidgets(): array
    {
        return [
            TotalBudgetMonthlySpendingsLineChart::class,
            TotalBudgetParentCategoryRankingBarChart::class,
            TotalBudgetCategoryRankingBarChart::class,
            TotalBudgetMonthlyIncomesLineChart::class,
        ];
    }
}
