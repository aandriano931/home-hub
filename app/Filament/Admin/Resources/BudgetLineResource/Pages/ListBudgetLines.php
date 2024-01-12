<?php

namespace App\Filament\Admin\Resources\BudgetLineResource\Pages;

use App\Filament\Admin\Resources\BudgetLineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBudgetLines extends ListRecords
{
    protected static string $resource = BudgetLineResource::class;
    protected static ?string $title = 'Liste des lignes de budget';
    protected static ?string $breadcrumb = 'Liste';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nouvelle ligne de budget'),
        ];
    }
}
