<?php

namespace App\Filament\Admin\Resources\BankTransactionResource\Pages;

use App\Filament\Admin\Resources\BankTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBankTransactions extends ListRecords
{
    protected static string $resource = BankTransactionResource::class;
    protected static ?string $title = 'Liste des transactions';
    protected static ?string $breadcrumb = 'Liste';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nouvelle transaction'),
        ];
    }
}
