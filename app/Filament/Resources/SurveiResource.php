<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveiResource\Pages;
use App\Models\Survei;
use Filament\Forms;
use Filament\Schemas\Schema;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SurveiResource extends Resource
{
    protected static ?string $model = Survei::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Survei Kepuasan (SKM)';
    protected static ?string $modelLabel = 'Hasil Survei';
    protected static ?string $pluralModelLabel = 'Hasil Survei';
    protected static string | \UnitEnum | null $navigationGroup = 'Laporan & Analitik';

    public static function Schema(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tracking_id')
                    ->relationship('tracking', 'nomor_lacak')
                    ->disabled(),
                Forms\Components\Select::make('antrian_id')
                    ->relationship('antrian', 'nomor_antrian')
                    ->disabled(),
                Forms\Components\Select::make('dinas_id')
                    ->relationship('dinas', 'nama')
                    ->disabled(),
                Forms\Components\Select::make('layanan_id')
                    ->relationship('layanan', 'nama_layanan')
                    ->disabled(),
                Forms\Components\TextInput::make('skor_kepuasan')
                    ->label('Bintang / Kepuasan (1-5)')
                    ->disabled(),
                Forms\Components\Textarea::make('ulasan')
                    ->label('Umpan Balik / Ulasan')
                    ->disabled()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tracking.nomor_lacak')
                    ->label('Nomor Lacak')
                    ->default('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dinas.kode')
                    ->label('Dinas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('layanan.nama_layanan')
                    ->label('Layanan')
                    ->limit(20)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('skor_kepuasan')
                    ->label('Rating')
                    ->formatStateUsing(fn (int $state): string => str_repeat('⭐', $state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('ulasan')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Tanggal Pengisian')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('dinas_id')
                    ->relationship('dinas', 'nama')
                    ->label('Gerai Dinas'),
                Tables\Filters\SelectFilter::make('skor_kepuasan')
                    ->options([
                        '5' => '⭐⭐⭐⭐⭐ (Sangat Puas)',
                        '4' => '⭐⭐⭐⭐ (Puas)',
                        '3' => '⭐⭐⭐ (Cukup)',
                        '2' => '⭐⭐ (Kurang)',
                        '1' => '⭐ (Sangat Kurang)',
                    ])
                    ->label('Rating Bintang'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSurvei::route('/'),
        ];
    }
}
