<?php

namespace App\Filament\Admin\Resources\BankCategoryResource\Pages;

use App\Filament\Admin\Resources\BankCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBankCategory extends CreateRecord
{
    protected static string $resource = BankCategoryResource::class;
    protected static ?string $title = 'Créer une catégorie';
    protected static ?string $breadcrumb = 'Créer';
}
