<?php

namespace App\Filament\Admin\Resources\BudgetResource\Pages;

use App\Filament\Admin\Resources\BudgetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBudget extends EditRecord
{
    protected static string $resource = BudgetResource::class;
    protected static ?string $title = 'Modifier le budget';
    protected static ?string $breadcrumb = 'Modifier';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Supprimer le budget'),
        ];
    }
}
