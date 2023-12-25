<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\MonthlyBudgetCategoryRankingBarChart;
use App\Filament\App\Widgets\MonthlyBudgetParentCategoryRankingBarChart;
use App\Filament\App\Widgets\TotalBudgetMonthlySpendingsLineChart;
use App\Filament\App\Widgets\MonthlyBudgetCategoryPieChart;
use App\Filament\App\Widgets\MonthlyBudgetParentCategoryPieChart;
use App\Filament\App\Widgets\TotalBudgetMonthlyIncomesLineChart;
use App\Filament\App\Widgets\YearlyBudgetCategoryPieChart;
use App\Filament\App\Widgets\YearlyBudgetParentCategoryPieChart;
use Filament\Pages\Page;

class Budget extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.budget';

    protected function getHeaderWidgets(): array
    {
        return [
            TotalBudgetMonthlySpendingsLineChart::class,
            TotalBudgetMonthlyIncomesLineChart::class,
            MonthlyBudgetParentCategoryPieChart::class,
            MonthlyBudgetCategoryPieChart::class,
            MonthlyBudgetParentCategoryRankingBarChart::class,
            MonthlyBudgetCategoryRankingBarChart::class,
            YearlyBudgetParentCategoryPieChart::class,
            YearlyBudgetCategoryPieChart::class,
        ];
    }
}
