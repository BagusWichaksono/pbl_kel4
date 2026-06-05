<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EvaluationQuestionResource\Pages;
use App\Models\EvaluationQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class EvaluationQuestionResource extends Resource
{
    protected static ?string $model = EvaluationQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Pertanyaan Evaluasi';

    protected static ?string $modelLabel = 'Pertanyaan Evaluasi';

    protected static ?string $pluralModelLabel = 'Daftar Pertanyaan Evaluasi';

    protected static ?string $navigationGroup = 'Manajemen Testing';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Pertanyaan')
                    ->description('Pertanyaan ini akan ditampilkan kepada tester saat mengisi form evaluasi akhir.')
                    ->schema([
                        Forms\Components\Textarea::make('question_text')
                            ->label('Teks Pertanyaan')
                            ->required()
                            ->rows(3)
                            ->placeholder('Contoh: Seberapa mudah aplikasi ini digunakan?')
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('min_scale')
                                ->label('Skala Minimum')
                                ->numeric()
                                ->required()
                                ->default(1)
                                ->minValue(1)
                                ->maxValue(9)
                                ->helperText('Nilai terendah yang bisa dipilih tester.'),

                            Forms\Components\TextInput::make('max_scale')
                                ->label('Skala Maksimum')
                                ->numeric()
                                ->required()
                                ->default(10)
                                ->minValue(2)
                                ->maxValue(10)
                                ->helperText('Nilai tertinggi yang bisa dipilih tester.'),

                            Forms\Components\TextInput::make('order')
                                ->label('Urutan Tampil')
                                ->numeric()
                                ->required()
                                ->default(0)
                                ->helperText('Angka kecil tampil lebih dulu.'),
                        ]),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Pertanyaan yang tidak aktif tidak akan muncul di form evaluasi.')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable()
                    ->width(80),

                Tables\Columns\TextColumn::make('question_text')
                    ->label('Pertanyaan')
                    ->searchable()
                    ->wrap()
                    ->limit(100),

                Tables\Columns\TextColumn::make('scale_range')
                    ->label('Skala')
                    ->getStateUsing(fn ($record) => $record->min_scale . ' – ' . $record->max_scale)
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('answers_count')
                    ->label('Jumlah Jawaban')
                    ->counts('answers')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(updated_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    }),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif saja')
                    ->falseLabel('Nonaktif saja')
                    ->placeholder('Semua'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn ($record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn ($record) => $record->is_active ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => $record->is_active ? 'Nonaktifkan Pertanyaan?' : 'Aktifkan Pertanyaan?')
                    ->modalDescription(fn ($record) => $record->is_active
                        ? 'Pertanyaan ini tidak akan muncul di form evaluasi tester.'
                        : 'Pertanyaan ini akan kembali muncul di form evaluasi tester.')
                    ->action(function ($record) {
                        $record->update(['is_active' => ! $record->is_active]);
                        Notification::make()
                            ->title($record->is_active ? 'Pertanyaan diaktifkan.' : 'Pertanyaan dinonaktifkan.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan Semua Dipilih')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan Semua Dipilih')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEvaluationQuestions::route('/'),
            'create' => Pages\CreateEvaluationQuestion::route('/create'),
            'edit'   => Pages\EditEvaluationQuestion::route('/{record}/edit'),
        ];
    }
}
