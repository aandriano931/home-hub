<?php

namespace App\Filament\App\Pages\Budget;

use Filament\Pages\Page;

class BudgetConfigurator extends Page
{
    protected static string $view = 'filament.app.pages.budget-configurator';
    protected static ?string $navigationGroup = 'Budget';
    protected static ?string $navigationLabel = 'Configurateur';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Configurateur de budget';
    protected ?string $subheading = 'Outil permettant de configurer les virements nécessaires pour le budget mensuel';



}
