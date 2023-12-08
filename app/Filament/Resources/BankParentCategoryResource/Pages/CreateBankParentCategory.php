<?php

namespace App\Filament\Resources\BankParentCategoryResource\Pages;

use App\Filament\Resources\BankParentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBankParentCategory extends CreateRecord
{
    protected static string $resource = BankParentCategoryResource::class;
    protected static ?string $title = 'Créer une catégorie parent';
    protected static ?string $breadcrumb = 'Créer';
}
