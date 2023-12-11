<?php

namespace App\Filament\Admin\Resources\BankTransactionResource\Pages;

use App\Filament\Admin\Resources\BankTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBankTransaction extends CreateRecord
{
    protected static string $resource = BankTransactionResource::class;
}
