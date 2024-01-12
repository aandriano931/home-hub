<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BudgetResource\Pages;
use App\Models\Budget\Budget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;
    protected static ?string $label = 'Budget';
    protected static ?string $navigationGroup = 'Budget';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Libellé')
                    ->required()
                    ->maxLength(100),
                Forms\Components\DatePicker::make('expiration_date')
                    ->label('Date d\'expiration')
                    ->required()
                    ->default(now()->addMonths(3))
                    ->minDate(now())
                    ->maxDate(now()->addMonths(6)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Libellé'),
                Tables\Columns\TextColumn::make('expiration_date')
                    ->dateTime('d/m/Y')
                    ->label('Date d\'expiration'),
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
            'index' => Pages\ListBudgets::route('/'),
            'create' => Pages\CreateBudget::route('/create'),
            'edit' => Pages\EditBudget::route('/{record}/edit'),
        ];
    }

    public static function  getPluralLabel(): string
    {
        return 'Budgets';
    }
}
