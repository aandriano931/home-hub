<?php
 
namespace App\Filament\Pages;
 
class Administration extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-s-cog-6-tooth';
    protected static ?string $navigationLabel = 'Administration';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Espace d\'Administration';
    protected ?string $subheading = 'Vous trouverez ici tous les outils pour configurer les données de l\'application.';

}
