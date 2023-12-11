<?php

namespace App\Filament\Admin\Resources\BankParentCategoryResource\Pages;

use App\Filament\Admin\Resources\BankParentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBankParentCategory extends EditRecord
{
    protected static string $resource = BankParentCategoryResource::class;
    protected static ?string $title = 'Modifier la catégorie parent';
    protected static ?string $breadcrumb = 'Modifier';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Supprimer la catégorie parent'),
        ];
    }
}
