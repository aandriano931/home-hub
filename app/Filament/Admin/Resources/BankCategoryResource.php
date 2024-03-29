<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BankCategoryResource\Pages;
use App\Filament\Admin\Resources\BankCategoryResource\RelationManagers;
use App\Filament\Admin\Resources\BankParentCategoryResource;
use App\Models\Bank\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankCategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $label = 'Catégorie';
    protected static ?string $navigationGroup = 'Banque';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Libellé')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('picto')
                    ->label('Icône')
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->label('Couleur'),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        BankParentCategoryResource::DEBIT_TYPE => 'Dépenses',
                        BankParentCategoryResource::CREDIT_TYPE => 'Revenus',
                    ])
                    ->default('débit'),
                Forms\Components\Select::make('bank_parent_category_id')
                    ->relationship('parentCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Catégorie parente'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('Libellé')
                ->searchable(true)
                ->sortable(),
            Tables\Columns\TextColumn::make('type')
                ->label('Type')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    BankParentCategoryResource::CREDIT_TYPE => 'success',
                    BankParentCategoryResource::DEBIT_TYPE => 'danger',
                }),
            Tables\Columns\TextColumn::make('picto')
                ->label('Icône'),
            Tables\Columns\TextColumn::make('color')
                ->label('Couleur'),
            Tables\Columns\TextColumn::make('parentCategory.name')
                ->label('Parent'),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('type')
                ->options([
                    BankParentCategoryResource::DEBIT_TYPE => 'Dépenses',
                    BankParentCategoryResource::CREDIT_TYPE => 'Revenus',
                ]),
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
            'index' => Pages\ListBankCategories::route('/'),
            'create' => Pages\CreateBankCategory::route('/create'),
            'edit' => Pages\EditBankCategory::route('/{record}/edit'),
        ];
    }

    public static function  getPluralLabel(): string
    {
        return 'Catégories';
    }
}
