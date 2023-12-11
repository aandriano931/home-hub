<?php

namespace App\Filament\Admin\Resources\BankAccountResource\Pages;

use App\Filament\Admin\Resources\BankAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBankAccounts extends ListRecords
{
    protected static string $resource = BankAccountResource::class;
    protected static ?string $title = 'Liste des comptes bancaires';
    protected static ?string $breadcrumb = 'Liste';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nouveau compte bancaire'),
        ];
    }
}
