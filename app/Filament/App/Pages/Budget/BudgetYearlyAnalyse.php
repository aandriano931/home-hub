<?php

namespace App\Filament\App\Pages\Budget;


use App\Filament\App\Widgets\Budget\Yearly\YearlyBudgetCategoryPieChart;
use App\Filament\App\Widgets\Budget\Yearly\YearlyBudgetCategoryRankingBarChart;
use App\Filament\App\Widgets\Budget\Yearly\YearlyBudgetParentCategoryPieChart;
use App\Filament\App\Widgets\Budget\Yearly\YearlyBudgetParentCategoryRankingBarChart;
use Filament\Pages\Page;

class BudgetYearlyAnalyse extends Page
{
    protected static string $view = 'filament.app.pages.budget-yearly-analyse';
    protected static ?string $navigationGroup = 'Budget';
    protected static ?string $navigationLabel = 'Analyse annuelle';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Analyse annuelle du budget';
    protected ?string $subheading = 'Analyse des dépenses annuelles sur le compte joint depuis 2020';

    protected function getHeaderWidgets(): array
    {
        return [
            YearlyBudgetParentCategoryPieChart::make(['isInitializedWithPreviousYear' => true]),
            YearlyBudgetParentCategoryPieChart::class,
            YearlyBudgetCategoryPieChart::make(['isInitializedWithPreviousYear' => true]),
            YearlyBudgetCategoryPieChart::class,
            YearlyBudgetParentCategoryRankingBarChart::make(['isInitializedWithPreviousYear' => true]),
            YearlyBudgetParentCategoryRankingBarChart::class,
            YearlyBudgetCategoryRankingBarChart::make(['isInitializedWithPreviousYear' => true]),
            YearlyBudgetCategoryRankingBarChart::class,
        ];
    }
}
