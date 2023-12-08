<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankTransactionResource\Pages;
use App\Filament\Resources\BankTransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankTransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Libellé')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('operation_date')
                    ->label('Date d\'opération')
                    ->required()
                    ->maxDate(now()),
                Forms\Components\DatePicker::make('value_date')
                    ->label('Date de valeur')
                    ->required()
                    ->maxDate(now()),
                Forms\Components\TextInput::make('credit')
                    ->label('Crédit')
                    ->numeric()
                    ->prefix('€')
                    ->default(0),
                Forms\Components\TextInput::make('debit')
                    ->label('Débit')
                    ->numeric()
                    ->prefix('€')
                    ->default(0),
                Forms\Components\Select::make('bank_account_id')
                    ->relationship('account', 'label')
                    ->searchable()
                    ->preload()
                    ->label('Compte bancaire')
                    ->required(),
                Forms\Components\Select::make('bank_transaction_category_id')
                    ->relationship('category', 'label')
                    ->searchable()
                    ->preload()
                    ->label('Catégorie'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListBankTransactions::route('/'),
            'create' => Pages\CreateBankTransaction::route('/create'),
            'edit' => Pages\EditBankTransaction::route('/{record}/edit'),
        ];
    }
}
