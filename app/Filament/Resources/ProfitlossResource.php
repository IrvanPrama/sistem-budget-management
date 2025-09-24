<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfitlossResource\Pages;
use App\Filament\Resources\ProfitlossResource\RelationManagers;
use App\Models\Profitloss;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfitlossResource extends Resource
{
    protected static ?string $model = Profitloss::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Keuangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('income')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('operation_fee')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('employee_salary')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('other_fee')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_cost')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('profit')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('income')
                    ->searchable(),
                Tables\Columns\TextColumn::make('operation_fee')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee_salary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('other_fee')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_cost')
                    ->searchable(),
                Tables\Columns\TextColumn::make('profit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListProfitlosses::route('/'),
            'create' => Pages\CreateProfitloss::route('/create'),
            'edit' => Pages\EditProfitloss::route('/{record}/edit'),
        ];
    }
}
