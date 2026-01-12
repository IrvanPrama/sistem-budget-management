<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Jika user login role = 0 (admin), tampilkan semua
        if (auth()->check() && auth()->user()->role === 0) {
            return $query;
        }

        // Selain role 0 → filter berdasarkan banjar user
        if (auth()->check()) {
            return $query->where('employee_id', auth()->user()->id);
        }

        // Default kalau tidak login (harusnya nggak terjadi di Filament)
        return $query->whereRaw('1=0');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('employee_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nomor_tlp')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('adress')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('position')
                    ->required()
                    ->options([
                        'Designer' => 'Designer',
                        'Admin' => 'Admin',
                        'Production' => 'Production',
                        'Marketing' => 'Marketing',
                        'Sopir' => 'Sopir',
                    ]),
                Forms\Components\TextInput::make('salary')
                    ->label('Gaji Pokok')
                    ->numeric()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_tlp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('adress')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salary')
                    ->money('IDR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
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
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->role == 0), // hanya role 0
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->role == 0),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
