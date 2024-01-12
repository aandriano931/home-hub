<?php

namespace App\Filament\Admin\Resources\BudgetContributorResource\Pages;

use App\Filament\Admin\Resources\BudgetContributorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBudgetContributor extends CreateRecord
{
    protected static string $resource = BudgetContributorResource::class;
    protected static ?string $title = 'Créer un contributeur';
    protected static ?string $breadcrumb = 'Créer';

}
