<?php

namespace App\Filament\Admin\Resources\BankParentCategoryResource\Pages;

use App\Filament\Admin\Resources\BankParentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBankParentCategories extends ListRecords
{
    protected static string $resource = BankParentCategoryResource::class;
    protected static ?string $title = 'Liste des catégories parents';
    protected static ?string $breadcrumb = 'Liste';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nouvelle catégorie parent'),
        ];
    }
}
