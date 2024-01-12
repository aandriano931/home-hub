<?php

namespace App\Filament\Admin\Resources\BudgetContributorResource\Pages;

use App\Filament\Admin\Resources\BudgetContributorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBudgetContributors extends ListRecords
{
    protected static string $resource = BudgetContributorResource::class;
    protected static ?string $title = 'Liste des contributeurs';
    protected static ?string $breadcrumb = 'Liste';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nouveau contributeur'),
        ];
    }
}
