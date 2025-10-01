<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetResource\Pages;
use App\Models\Budget;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Keuangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_name')
                    ->label('Project')
                    ->options(Project::pluck('project_name', 'project_name'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $project = Project::where('project_name', $state)->first();
                            if ($project) {
                                $set('client', $project->client);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('client')
                    ->required()
                    ->readonly()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('transaction_date')
                   ->label('Tgl Transaksi')
                   ->required(),

                Forms\Components\TextInput::make('expense_name')
                    ->label('Nama Pengeluaran')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('estimate')
                    ->label('Estimasi Pengeluaran')
                    ->required()
                    ->numeric()
                    ->prefix('Rp '),

                Forms\Components\TextInput::make('expenses')
                    ->label('Realisasi Pengeluaran')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(
                        fn ($state, callable $set, $get) => $set('profit_loss', ($get('estimate') ?? 0) - ($state ?? 0))
                    ),

                Forms\Components\TextInput::make('profit_loss')
                    ->label('Profit / Loss')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(true),

                Forms\Components\FileUpload::make('bukti_transfer')
                    ->label('Bukti Transfer')
                    ->disk('public')
                    ->directory('bukti_transfer')
                    ->image()
                    ->preserveFilenames(), // optional
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tgl Transaksi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('project_name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('client')
                    ->searchable(),

                Tables\Columns\TextColumn::make('expense_name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('estimate')
                    ->label('Estimasi Pengeluaran')
                    ->numeric()
                    ->money('IDR')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Estimasi')
                            ->money('IDR'),
                    ]),

                Tables\Columns\TextColumn::make('expenses')
                    ->label('Realisasi Pengeluaran')
                    ->numeric()
                    ->money('IDR')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Realisasi')
                            ->money('IDR'),
                    ]),

                Tables\Columns\TextColumn::make('profit_loss')
                    ->label('Profit / Loss')
                    ->money('IDR')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                    ->summarize([
                        Tables\Columns\Summarizers\Summarizer::make()
                            ->label('Total Profit / Loss')
                            ->using(fn ($query) => $query->get()->sum('profit_loss'))
                            ->money('IDR'),
                    ]),

                ImageColumn::make('bukti_transfer')
                    ->label('Bukti Transfer')
                    ->disk('public') // otomatis generate url ke storage/public
                    ->square(), // optional: biar proporsional
            ])
            ->filters([
                SelectFilter::make('project_name')
                    ->label('Project')
                    ->options(Project::pluck('project_name', 'project_name'))
                    ->searchable(),

                SelectFilter::make('client')
                    ->label('Client')
                    ->options(Project::pluck('client', 'client'))
                    ->searchable(),

                SelectFilter::make('transaction_date')
                    ->label('Tgl Transaksi')
                    ->options(Budget::pluck('transaction_date', 'transaction_date'))
                    ->searchable(),
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->headerActions([
                Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document')
                    ->action(function ($livewire) {
                        $budgets = $livewire->getFilteredTableQuery()->get();

                        $pdf = Pdf::loadView('exports.budgets', [
                            'budgets' => $budgets,
                        ]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            'budgets.pdf'
                        );
                    }),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBudgets::route('/'),
            'create' => Pages\CreateBudget::route('/create'),
            'edit' => Pages\EditBudget::route('/{record}/edit'),
        ];
    }
}
