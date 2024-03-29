<?php

namespace App\Filament\Admin\Resources\BankAccountResource\Pages;

use App\Filament\Admin\Resources\BankAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBankAccount extends CreateRecord
{
    protected static string $resource = BankAccountResource::class;
    protected static ?string $title = 'Créer un compte bancaire';
    protected static ?string $breadcrumb = 'Créer';

}
