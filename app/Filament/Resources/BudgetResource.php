<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetResource\Pages;
use App\Imports\BudgetsImport;
use App\Models\Budget;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Keuangan';
    protected static ?int $navigationSort = 3;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('project_name')
                ->label('Nama Projek')
                ->options(Project::pluck('project_name', 'project_name'))
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $project = Project::where('project_name', $state)->first();
                    if ($project) {
                        $set('client', $project->client);
                    }
                }),

            TextInput::make('client')
                ->label('Nama Klien')
                ->readonly()
                ->required(),

            DatePicker::make('transaction_date')
                ->label('Tanggal Transaksi')
                ->required(),

            TextInput::make('expense_name')
                ->label('Nama Pengeluaran')
                ->required(),

            TextInput::make('estimate')
                ->label('Estimasi')
                ->numeric()
                ->prefix('Rp ')
                ->required(),

            TextInput::make('expenses')
                ->label('Realisasi')
                ->numeric()
                ->reactive(),

            TextInput::make('budget')
                ->label('Anggaran Proyek')
                ->numeric()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, $get) => $set('profit_loss', ($state ?? 0) - ($get('expenses') ?? 0))
                ),

            TextInput::make('profit_loss')
                ->label('Laba / Rugi')
                ->numeric()
                ->disabled()
                ->dehydrated(true),

            FileUpload::make('bukti_transfer')
                ->label('Bukti Transfer')
                ->disk('public')
                ->directory('bukti_transfer')
                ->image(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')->label('Tanggal')->date(),
                TextColumn::make('project_name')->label('Projek'),
                TextColumn::make('client')->label('Klien'),
                TextColumn::make('expense_name')->label('Pengeluaran'),

                TextColumn::make('estimate')
                    ->label('Estimasi')
                    ->money('IDR')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('IDR')),

                TextColumn::make('expenses')
                    ->label('Realisasi')
                    ->money('IDR')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('IDR')),

                TextColumn::make('profit_loss')
                    ->label('Laba / Rugi')
                    ->money('IDR')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),

                ImageColumn::make('bukti_transfer')->label('Bukti'),
            ])
            ->headerActions([
                Action::make('exportPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document')
                    ->action(function ($livewire) {
                        $budgets = $livewire->getFilteredTableQuery()->get();
                        $pdf = Pdf::loadView('exports.budgets', compact('budgets'));

                        return response()->streamDownload(fn () => print ($pdf->output()), 'laporan-budget.pdf');
                    }),

                Action::make('import')
                    ->label('Impor CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        FileUpload::make('file')
                            ->label('File CSV')
                            ->disk('local')
                            ->directory('imports')
                            ->acceptedFileTypes(['text/csv'])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $path = Storage::disk('local')->path($data['file']);
                        Excel::queueImport(new BudgetsImport(), $path);
                    }),
            ])
            ->actions([
                Actions\EditAction::make()->label('Ubah'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBudgets::route('/'),
            'create' => Pages\CreateBudget::route('/buat'),
            'edit' => Pages\EditBudget::route('/{record}/ubah'),
        ];
    }
}
