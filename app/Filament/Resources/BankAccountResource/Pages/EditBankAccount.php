<?php

namespace App\Filament\Resources\BankAccountResource\Pages;

use App\Filament\Resources\BankAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBankAccount extends EditRecord
{
    protected static string $resource = BankAccountResource::class;
    protected static ?string $title = 'Modifier le compte bancaire';
    protected static ?string $breadcrumb = 'Modifier';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Supprimer le compte bancaire'),
        ];
    }
}
