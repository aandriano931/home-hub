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
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Analyse mensuelle du budget';
    protected ?string $subheading = 'Analyse des dépenses mensuelles sur le compte joint depuis janvier 2020';

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
