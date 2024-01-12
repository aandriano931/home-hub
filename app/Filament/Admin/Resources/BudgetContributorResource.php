<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BudgetContributorResource\Pages;
use App\Models\Budget\BudgetContributor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetContributorResource extends Resource
{
    protected static ?string $model = BudgetContributor::class;
    protected static ?string $label = 'Contributeur';
    protected static ?string $navigationGroup = 'Budget';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Libellé')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('available_money')
                    ->label('Argent disponible')
                    ->numeric(),
                Forms\Components\Select::make('budget_id')
                    ->relationship('budget', 'budget.label')
                    ->searchable()
                    ->preload()
                    ->label('Budget')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'users.name')
                    ->searchable()
                    ->preload()
                    ->label('Utilisateur'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Libellé'),
                Tables\Columns\TextColumn::make('available_money')
                    ->label('Argent disponible'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur'),
                Tables\Columns\TextColumn::make('budget.label')
                    ->label('Budget'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBudgetContributors::route('/'),
            'create' => Pages\CreateBudgetContributor::route('/create'),
            'edit' => Pages\EditBudgetContributor::route('/{record}/edit'),
        ];
    }

    public static function  getPluralLabel(): string
    {
        return 'Contributeurs';
    }
}
