<?php

namespace App\Filament\App\Pages\Budget;

use App\Filament\App\Widgets\Budget\Macro\MacroBudgetCategoryPieChart;
use App\Filament\App\Widgets\Budget\Macro\MacroBudgetCategoryRankingBarChart;
use App\Filament\App\Widgets\Budget\Macro\MacroBudgetGlobalLineChart;
use App\Filament\App\Widgets\Budget\Macro\MacroBudgetParentCategoryPieChart;
use App\Filament\App\Widgets\Budget\Macro\MacroBudgetParentCategoryRankingBarChart;
use Filament\Pages\Page;

class BudgetMacroAnalyse extends Page
{
    protected static string $view = 'filament.app.pages.budget-macro-analyse';
    protected static ?string $navigationGroup = 'Budget';
    protected static ?string $navigationLabel = 'Analyse macro';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Analyse macro du budget';
    protected ?string $subheading = 'Analyse de l\'ensemble des dépenses sur le compte joint depuis janvier 2020';

    protected function getHeaderWidgets(): array
    {
        return [
            MacroBudgetGlobalLineChart::class,
            MacroBudgetParentCategoryRankingBarChart::class,
            MacroBudgetCategoryRankingBarChart::class,
            MacroBudgetParentCategoryPieChart::class,
            MacroBudgetCategoryPieChart::class,
        ];
    }
}
