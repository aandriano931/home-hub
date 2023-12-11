<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BankTransactionResource\Pages;
use App\Filament\Admin\Resources\BankTransactionResource\RelationManagers;
use App\Models\Bank\Transaction;
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
                    ->suffix('€')
                    ->default(0),
                Forms\Components\TextInput::make('debit')
                    ->label('Débit')
                    ->numeric()
                    ->suffix('€')
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
                Tables\Columns\TextColumn::make('label')
                    ->label('Libellé')
                    ->searchable(true)
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie'),
                Tables\Columns\TextColumn::make('operation_date')
                    ->label('Date d\'opération')
                    ->dateTime('d/m/Y')
                    ->searchable(true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('value_date')
                    ->label('Date de valeur')
                    ->dateTime('d/m/Y')
                    ->searchable(true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('credit')
                    ->label('Crédit')
                    ->color('success')
                    ->money('eur'),
                Tables\Columns\TextColumn::make('debit')
                    ->label('Débit')
                    ->color('danger')
                    ->money('eur'),
                Tables\Columns\TextColumn::make('account.label')
                    ->label('Compte bancaire'),
            ])->defaultSort('operation_date', 'desc')
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
