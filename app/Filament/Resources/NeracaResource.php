<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NeracaResource\Pages;
use App\Filament\Resources\NeracaResource\RelationManagers;
use App\Models\Neraca;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NeracaResource extends Resource
{
    protected static ?string $model = Neraca::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('khas_bank')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('liability')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('equity')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('trade_receivable')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_asset')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_liability')
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
                Tables\Columns\TextColumn::make('khas_bank')
                    ->searchable(),
                Tables\Columns\TextColumn::make('liability')
                    ->searchable(),
                Tables\Columns\TextColumn::make('equity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('trade_receivable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('liability')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_asset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_liability')
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
            'index' => Pages\ListNeraca::route('/'),
            'create' => Pages\CreateNeraca::route('/create'),
            'edit' => Pages\EditNeraca::route('/{record}/edit'),
        ];
    }
}
