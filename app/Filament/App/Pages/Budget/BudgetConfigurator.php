<?php

namespace App\Filament\App\Pages\Budget;

use App\Models\Budget\Budget;
use App\Models\Budget\BudgetLine;
use Filament\Pages\Page;

class BudgetConfigurator extends Page
{
    protected static string $view = 'filament.app.pages.budget-configurator';
    protected static ?string $navigationGroup = 'Budget';
    protected static ?string $navigationLabel = 'Budget mensuel en cours';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Budget mensuel en cours';
    protected ?string $subheading = 'Informations sur le budget mensuel actuel et sur la méthode de calcul des parts utilisée.';
    public Budget $budget;
    public float $totalDebit;
    public float $totalCredit;

    public function mount()
    {
        $budget = Budget::with('budgetLines')->where('is_active', '=', true)->first();
        $this->budget = $budget;
        $this->totalCredit =$this->getBudgetTotalCredit($budget);
        $this->totalDebit =$this->getBudgetTotalDebit($budget);
    }

    private function getBudgetTotalDebit(Budget $budget): float
    {
        $totalDebit = 0;
        foreach ($budget->budgetLines as $budgetLine) {
            $totalDebit += $budgetLine->debit;
        }

        return $totalDebit;
    }

    private function getBudgetTotalCredit(Budget $budget): float
    {
        $totalCredit = 0;
        foreach ($budget->budgetLines as $budgetLine) {
            $totalCredit += $budgetLine->credit;
        }

        return $totalCredit;
    }

}
