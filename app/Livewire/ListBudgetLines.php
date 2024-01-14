<?php

namespace App\Livewire;

use App\Models\Budget\BudgetLine;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Livewire\Component;

class ListBudgetLines extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $budget;
    
    public function mount($budget)
    {
        $this->budget = $budget;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(BudgetLine::query()->where('budget_id', '=', $this->budget->id))
            ->heading('Détail des lignes du budget' )
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Libellé')
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('credit')
                    ->label('Crédit')
                    ->sortable()
                    ->color('success')
                    ->money('eur'),
                Tables\Columns\TextColumn::make('debit')
                    ->label('Débit')
                    ->sortable()
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
            ->defaultSort('debit', 'desc')
            ->paginated(false)
            ->searchable(false)
            ;
    }

    public function render()
    {
        return view('livewire.list-budget-lines');
    }
}
