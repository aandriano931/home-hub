<?php

namespace App\Filament\Admin\Resources\BudgetLineResource\Pages;

use App\Filament\Admin\Resources\BudgetLineResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBudgetLine extends CreateRecord
{
    protected static string $resource = BudgetLineResource::class;
    protected static ?string $title = 'Créer une ligne de budget';
    protected static ?string $breadcrumb = 'Créer';

}
