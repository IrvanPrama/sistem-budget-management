<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfitLossResource\Pages;
use App\Models\ProfitLoss;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters;
use Filament\Tables\Table;

class ProfitLossResource extends Resource
{
    protected static ?string $model = ProfitLoss::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('year')
                   ->required(),
                Forms\Components\TextInput::make('transaction_name')
                    ->required(),
                Forms\Components\TextInput::make('transaction_type')
                    ->required(),
                Forms\Components\TextInput::make('income'),
                Forms\Components\TextInput::make('expense'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year')
                   ->sortable(),
                Tables\Columns\TextColumn::make('transaction_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('income')
                    ->searchable()
                    ->money('IDR')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Income')
                            ->money('IDR'),
                    ]),
                Tables\Columns\TextColumn::make('expense')
                    ->searchable()
                    ->money('IDR')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Expense')
                            ->money('IDR'),
                    ]),
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
                Filters\Filter::make('date')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data): void {
                        if ($data['from']) {
                            $query->whereDate('date', '>=', $data['from']);
                        }
                        if ($data['until']) {
                            $query->whereDate('date', '<=', $data['until']);
                        }
                    }),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfitLosses::route('/'),
            'create' => Pages\CreateProfitLoss::route('/create'),
            'edit' => Pages\EditProfitLoss::route('/{record}/edit'),
        ];
    }
}
