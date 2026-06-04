<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\EvaluationResultResource\Pages;
use App\Models\TestingReport;
use App\Models\EvaluationAnswer;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EvaluationResultResource extends Resource
{
    protected static ?string $model = TestingReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Hasil Evaluasi';

    protected static ?string $modelLabel = 'Hasil Evaluasi';

    protected static ?string $pluralModelLabel = 'Hasil Evaluasi Tester';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 2;

    /**
     * Hanya tampilkan laporan yang sudah ada jawaban evaluasinya,
     * dan hanya milik aplikasi developer yang sedang login.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('applicationTester.application', function (Builder $query) {
                $query->where('developer_id', Auth::id());
            })
            ->whereHas('evaluationAnswers');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('applicationTester.application.title')
                    ->label('Nama Aplikasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('applicationTester.tester.name')
                    ->label('Nama Tester')
                    ->searchable(),

                Tables\Columns\TextColumn::make('avg_rating')
                    ->label('Rata-rata Rating')
                    ->getStateUsing(fn ($record) => $record->averageRating() . ' / 10')
                    ->badge()
                    ->color(fn ($record) => match (true) {
                        $record->averageRating() >= 8  => 'success',
                        $record->averageRating() >= 5  => 'warning',
                        default                         => 'danger',
                    }),

                Tables\Columns\TextColumn::make('answers_count')
                    ->label('Pertanyaan Dijawab')
                    ->counts('evaluationAnswers')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Evaluasi')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(created_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('application')
                    ->label('Filter Aplikasi')
                    ->relationship(
                        'applicationTester.application',
                        'title',
                        fn (Builder $query) => $query->where('developer_id', Auth::id())
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat Detail'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Tester')
                    ->schema([
                        TextEntry::make('applicationTester.application.title')
                            ->label('Aplikasi yang Diuji'),

                        TextEntry::make('applicationTester.tester.name')
                            ->label('Nama Tester'),

                        TextEntry::make('avg_rating_display')
                            ->label('Rata-rata Rating')
                            ->getStateUsing(fn ($record) => $record->averageRating() . ' / 10')
                            ->badge()
                            ->color(fn ($record) => match (true) {
                                $record->averageRating() >= 8  => 'success',
                                $record->averageRating() >= 5  => 'warning',
                                default                         => 'danger',
                            }),
                    ])
                    ->columns(3),

                Section::make('Jawaban Evaluasi')
                    ->description('Rating yang diberikan tester untuk setiap aspek aplikasi.')
                    ->schema([
                        RepeatableEntry::make('evaluationAnswers')
                            ->label('')
                            ->schema([
                                TextEntry::make('question.question_text')
                                    ->label('Pertanyaan')
                                    ->columnSpan(3),

                                TextEntry::make('rating_display')
                                    ->label('Rating')
                                    ->getStateUsing(function ($record) {
                                        $rating = $record->rating;
                                        $max    = $record->question?->max_scale ?? 10;
                                        $stars  = str_repeat('★', $rating) . str_repeat('☆', $max - $rating);
                                        return "{$rating} / {$max}   {$stars}";
                                    })
                                    ->badge()
                                    ->color(fn ($record) => match (true) {
                                        $record->rating >= 8 => 'success',
                                        $record->rating >= 5 => 'warning',
                                        default              => 'danger',
                                    })
                                    ->columnSpan(1),

                                TextEntry::make('comment')
                                    ->label('Komentar')
                                    ->placeholder('Tidak ada komentar.')
                                    ->columnSpan(4),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Catatan & Bug dari Laporan Akhir')
                    ->schema([
                        TextEntry::make('catatan')
                            ->label('Catatan / Feedback Umum')
                            ->placeholder('Tidak ada catatan.')
                            ->columnSpanFull(),

                        TextEntry::make('bug_report')
                            ->label('Laporan Bug')
                            ->placeholder('Tidak ada laporan bug.')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvaluationResults::route('/'),
            'view'  => Pages\ViewEvaluationResult::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}
