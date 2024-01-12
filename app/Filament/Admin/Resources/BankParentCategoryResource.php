<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BankParentCategoryResource\Pages;
use App\Filament\Admin\Resources\BankParentCategoryResource\RelationManagers;
use App\Models\Bank\ParentCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankParentCategoryResource extends Resource
{
    public const CREDIT_TYPE = 'crédit';
    public const DEBIT_TYPE = 'débit';
    protected static ?string $navigationGroup = 'Banque';
    protected static ?string $model = ParentCategory::class;
    protected static ?string $label = 'Catégorie parent';

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
                        self::DEBIT_TYPE => 'Dépenses',
                        self::CREDIT_TYPE => 'Revenus',
                    ])
                    ->default(self::DEBIT_TYPE),
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
                        self::CREDIT_TYPE => 'success',
                        self::DEBIT_TYPE => 'danger',
                    }),
                Tables\Columns\TextColumn::make('picto')
                    ->label('Icône'),
                Tables\Columns\TextColumn::make('color')
                    ->label('Couleur'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        self::DEBIT_TYPE => 'Dépenses',
                        self::CREDIT_TYPE => 'Revenus',
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
            'index' => Pages\ListBankParentCategories::route('/'),
            'create' => Pages\CreateBankParentCategory::route('/create'),
            'edit' => Pages\EditBankParentCategory::route('/{record}/edit'),
        ];
    }

    public static function  getPluralLabel(): string
    {
        return 'Catégories parent';
    }
}
