<?php

namespace App\Filament\Admin\Resources\BudgetContributorResource\Pages;

use App\Filament\Admin\Resources\BudgetContributorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBudgetContributor extends EditRecord
{
    protected static string $resource = BudgetContributorResource::class;
    protected static ?string $title = 'Modifier le contributeur';
    protected static ?string $breadcrumb = 'Modifier';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Supprimer le contributeur'),
        ];
    }
}
