<?php

namespace App\Filament\Admin\Resources\BudgetLineResource\Pages;

use App\Filament\Admin\Resources\BudgetLineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBudgetLine extends EditRecord
{
    protected static string $resource = BudgetLineResource::class;
    protected static ?string $title = 'Modifier la ligne de budget';
    protected static ?string $breadcrumb = 'Modifier';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Supprimer la ligne de budget'),
        ];
    }
}
