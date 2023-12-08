<?php

namespace App\Filament\Resources\BankCategoryResource\Pages;

use App\Filament\Resources\BankCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBankCategory extends EditRecord
{
    protected static string $resource = BankCategoryResource::class;
    protected static ?string $title = 'Modifier la catégorie';
    protected static ?string $breadcrumb = 'Modifier';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Supprimer la catégorie'),
        ];
    }
}
