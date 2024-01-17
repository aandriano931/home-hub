<?php

namespace App\Livewire;

use App\Models\Budget\Budget;
use App\Models\Budget\BudgetContributor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Livewire\Component;

class ListBudgetContributors extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public ?Budget $budget = null;
    public ?float $totalCredit = null;
    public ?float $totalDebit = null;
    
    public function mount(?Budget $budget, ?float $totalCredit, ?float $totalDebit)
    {
        $this->budget = $budget;
        $this->totalCredit = $totalCredit;
        $this->totalDebit = $totalDebit;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(BudgetContributor::query()->where('budget_id', '=', $this->budget->id))
            ->heading('Participant(s) et parts à payer.')
            ->description('La méthode de calcul utilisée pour déterminer la part est la suivante: ratio (somme disponible de la personne / total disponible) multiplié par le total du budget et arrondi à la dizaine la plus proche.')
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Nom'),
                Tables\Columns\TextColumn::make('available_money')
                    ->label('Argent disponible en début de mois')
                    ->alignCenter()
                    ->color('success')
                    ->money('eur'),
                Tables\Columns\TextColumn::make('ratio')
                    ->getStateUsing(function(BudgetContributor $record) {
                        return $this->calculateRatio($record);
                    })
                    ->label('Ratio')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('part')
                    ->getStateUsing(function(BudgetContributor $record) {
                        return $this->calculatePart($record);
                    })
                    ->label('Part à payer')
                    ->alignCenter()
                    ->color('danger')
                    ->money('eur'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ])
            ->paginated(false)
            ->searchable(false)
            ;
    }

    public function calculateRatio(BudgetContributor $budgetContributor): float
    {
        $totalAvailable = 0;
        foreach($this->budget->contributors as $contributor) {
            $totalAvailable += $contributor->available_money;
        }

        return round($budgetContributor->available_money / $totalAvailable, 3);
    }

    public function calculatePart(BudgetContributor $budgetContributor): float
    {
        return round($this->calculateRatio($budgetContributor) * ($this->totalDebit - $this->totalCredit), -1);
    }

    public function render()
    {
        return view('livewire.list-budget-contributors');
    }
}
