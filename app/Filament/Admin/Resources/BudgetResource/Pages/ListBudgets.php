<?php

namespace App\Filament\Admin\Resources\BudgetResource\Pages;

use App\Filament\Admin\Resources\BudgetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBudgets extends ListRecords
{
    protected static string $resource = BudgetResource::class;
    protected static ?string $title = 'Liste des budgets';
    protected static ?string $breadcrumb = 'Liste';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nouveau budget'),
        ];
    }
}
