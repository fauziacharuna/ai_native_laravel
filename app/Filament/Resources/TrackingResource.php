<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackingResource\Pages;
use App\Models\Tracking;
use App\Models\TrackingLog;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class TrackingResource extends Resource
{
    protected static ?string $model = Tracking::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-magnifying-glass-circle';
    protected static ?string $navigationLabel = 'Tracking Layanan';
    protected static ?string $modelLabel = 'Berkas Lacak';
    protected static ?string $pluralModelLabel = 'Berkas Lacak';
    protected static string | \UnitEnum | null $navigationGroup = 'Layanan & Antrian';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('antrian_id')
                    ->relationship('antrian', 'nomor_antrian')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\Select::make('dinas_id')
                    ->relationship('dinas', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('layanan_id')
                    ->relationship('layanan', 'nama_layanan')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('nomor_lacak')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_pemohon')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nomor_hp')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\Select::make('status_sekarang')
                    ->options([
                        'submitted' => 'Berkas Diterima (Submitted)',
                        'in_process' => 'Sedang Diproses (In Process)',
                        'hold' => 'Ditangguhkan (Hold / Berkas Kurang)',
                        'ready' => 'Siap Diambil (Ready)',
                        'completed' => 'Selesai & Diserahkan (Completed)',
                    ])
                    ->required()
                    ->default('submitted'),
                Forms\Components\Textarea::make('catatan_terakhir')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('tanggal_selesai_estimasi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nomor_lacak')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('nama_pemohon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dinas.kode')
                    ->label('Dinas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('layanan.nama_layanan')
                    ->label('Layanan')
                    ->limit(20)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status_sekarang')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'submitted' => 'gray',
                        'in_process' => 'info',
                        'hold' => 'danger',
                        'ready' => 'warning',
                        'completed' => 'success',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai_estimasi')
                    ->dateTime()
                    ->label('Estimasi Selesai')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('dinas_id')
                    ->relationship('dinas', 'nama')
                    ->label('Gerai Dinas'),
                Tables\Filters\SelectFilter::make('status_sekarang')
                    ->options([
                        'submitted' => 'Berkas Diterima',
                        'in_process' => 'Sedang Diproses',
                        'hold' => 'Ditangguhkan',
                        'ready' => 'Siap Diambil',
                        'completed' => 'Selesai',
                    ])
                    ->label('Status Berkas'),
            ])
            ->actions([
                // Update Status & Log Action
                Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-m-arrow-path')
                    ->color('info')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'submitted' => 'Berkas Diterima (Submitted)',
                                'in_process' => 'Sedang Diproses (In Process)',
                                'hold' => 'Ditangguhkan (Hold / Berkas Kurang)',
                                'ready' => 'Siap Diambil (Ready)',
                                'completed' => 'Selesai & Diserahkan (Completed)',
                            ])
                            ->required()
                            ->default(fn (Tracking $record) => $record->status_sekarang),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Perubahan')
                            ->required()
                            ->placeholder('e.g. Berkas sedang diverifikasi tim teknis atau detail kekurangan dokumen...'),
                    ])
                    ->action(function (Tracking $record, array $data) {
                        $record->update([
                            'status_sekarang' => $data['status'],
                            'catatan_terakhir' => $data['catatan'],
                        ]);

                        TrackingLog::create([
                            'tracking_id' => $record->id,
                            'status' => $data['status'],
                            'petugas_id' => auth()->id(),
                            'catatan' => $data['catatan'],
                        ]);
                    }),

                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTracking::route('/'),
        ];
    }
}
