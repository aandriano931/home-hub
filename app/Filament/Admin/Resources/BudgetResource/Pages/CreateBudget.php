<?php

namespace App\Filament\Admin\Resources\BudgetResource\Pages;

use App\Filament\Admin\Resources\BudgetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBudget extends CreateRecord
{
    protected static string $resource = BudgetResource::class;
    protected static ?string $title = 'Créer un budget';
    protected static ?string $breadcrumb = 'Créer';

}
