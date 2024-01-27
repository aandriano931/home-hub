<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BudgetLineResource\Pages;
use App\Models\Budget\BudgetLine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetLineResource extends Resource
{
    protected static ?string $model = BudgetLine::class;
    protected static ?string $label = 'Ligne de budget';
    protected static ?string $navigationGroup = 'Budget';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('label')
                ->label('Libellé')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('credit')
                ->label('Crédit')
                ->numeric()
                ->suffix('€')
                ->default(0),
            Forms\Components\TextInput::make('debit')
                ->label('Débit')
                ->numeric()
                ->suffix('€')
                ->default(0),
            Forms\Components\Select::make('budget_id')
                ->relationship('budget', 'label')
                ->searchable()
                ->preload()
                ->label('Budget')
                ->required(),
            Forms\Components\Select::make('bank_category_id')
                ->relationship('category', 'bank_category.name')
                ->searchable()
                ->preload()
                ->label('Catégorie'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('label')
                ->label('Libellé')
                ->searchable(true)
                ->sortable()
                ->limit(50),
            Tables\Columns\TextColumn::make('category.name')
                ->label('Catégorie'),
            Tables\Columns\TextColumn::make('credit')
                ->label('Crédit')
                ->color('success')
                ->money('eur'),
            Tables\Columns\TextColumn::make('debit')
                ->label('Débit')
                ->color('danger')
                ->money('eur'),
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
            'index' => Pages\ListBudgetLines::route('/'),
            'create' => Pages\CreateBudgetLine::route('/create'),
            'edit' => Pages\EditBudgetLine::route('/{record}/edit'),
        ];
    }

    public static function  getPluralLabel(): string
    {
        return 'Lignes de budget';
    }
}
