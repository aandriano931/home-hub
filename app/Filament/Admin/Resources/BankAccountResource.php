<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BankAccountResource\Pages;
use App\Filament\Admin\Resources\BankAccountResource\RelationManagers;
use App\Models\Bank\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankAccountResource extends Resource
{
    protected static ?string $model = Account::class;
    protected static ?string $label = 'Compte bancaire';
    protected static ?string $navigationGroup = 'Banque';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                ->label('Libellé')
                ->required()
                ->maxLength(100),
            Forms\Components\TextInput::make('alias')
                ->label('Alias')
                ->required()
                ->maxLength(100),
            Forms\Components\TextInput::make('holder')
                ->label('Propriétaire(s)')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('number')
                ->label('Numéro')
                ->required()
                ->maxLength(30),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Libellé'),
                Tables\Columns\TextColumn::make('alias')
                    ->label('Alias'),
                Tables\Columns\TextColumn::make('holder')
                    ->label('Propriétaire(s)'),
                Tables\Columns\TextColumn::make('number')
                    ->label('Numéro'),
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
            'index' => Pages\ListBankAccounts::route('/'),
            'create' => Pages\CreateBankAccount::route('/create'),
            'edit' => Pages\EditBankAccount::route('/{record}/edit'),
        ];
    }

    public static function  getPluralLabel(): string
    {
        return 'Comptes bancaires';
    }
}
