<?php

namespace App\Filament\App\Pages\Budget;

use App\Filament\App\Widgets\Budget\Monthly\MonthlyBudgetCategoryPieChart;
use App\Filament\App\Widgets\Budget\Monthly\MonthlyBudgetCategoryRankingBarChart;
use App\Filament\App\Widgets\Budget\Monthly\MonthlyBudgetParentCategoryPieChart;
use App\Filament\App\Widgets\Budget\Monthly\MonthlyBudgetParentCategoryRankingBarChart;
use Filament\Pages\Page;

class BudgetMonthlyAnalyse extends Page
{
    protected static string $view = 'filament.app.pages.budget-yearly-analyse';
    protected static ?string $navigationGroup = 'Budget';
    protected static ?string $navigationLabel = 'Analyse mensuelle';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Analyse mensuelle du budget';
    protected ?string $subheading = 'Analyse des dépenses mensuelles sur le compte joint depuis janvier 2020';

    protected function getHeaderWidgets(): array
    {
        return [
            MonthlyBudgetParentCategoryPieChart::make(['isInitializedWithPreviousMonth' => true]),
            MonthlyBudgetParentCategoryPieChart::class,
            MonthlyBudgetCategoryPieChart::make(['isInitializedWithPreviousMonth' => true]),
            MonthlyBudgetCategoryPieChart::class,
            MonthlyBudgetParentCategoryRankingBarChart::make(['isInitializedWithPreviousMonth' => true]),
            MonthlyBudgetParentCategoryRankingBarChart::class,
            MonthlyBudgetCategoryRankingBarChart::make(['isInitializedWithPreviousMonth' => true]),
            MonthlyBudgetCategoryRankingBarChart::class,
        ];
    }
}
