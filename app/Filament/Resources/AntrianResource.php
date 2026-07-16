<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AntrianResource\Pages;
use App\Models\Antrian;
use App\Models\Tracking;
use App\Models\TrackingLog;
use Filament\Forms;

use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Carbon\Carbon;

class AntrianResource extends Resource
{
    protected static ?string $model = Antrian::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Antrian Digital';
    protected static ?string $modelLabel = 'Antrian';
    protected static ?string $pluralModelLabel = 'Antrian';
    protected static string | \UnitEnum | null $navigationGroup = 'Layanan & Antrian';

    public static function Schema(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dinas_id')
                    ->relationship('dinas', 'nama')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('layanan_id', null)),
                Forms\Components\Select::make('layanan_id')
                    ->label('Layanan')
                    ->relationship('layanan', 'nama_layanan', fn ($query, callable $get) => 
                        $query->where('dinas_id', $get('dinas_id'))
                    )
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('nomor_antrian')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('nomor_urut')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('nama_pemohon')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nomor_hp')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\Select::make('tipe_pendaftaran')
                    ->options([
                        'online' => 'Online',
                        'offline' => 'Offline',
                    ])
                    ->required()
                    ->default('offline'),
                Forms\Components\Select::make('status')
                    ->options([
                        'waiting' => 'Waiting (Menunggu)',
                        'calling' => 'Calling (Dipanggil)',
                        'serving' => 'Serving (Dilayani)',
                        'skipped' => 'Skipped (Dilewati)',
                        'completed' => 'Completed (Selesai)',
                    ])
                    ->required()
                    ->default('waiting'),
                Forms\Components\TextInput::make('loket_nomor')
                    ->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('waktu_ambil', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nomor_antrian')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('dinas.kode')
                    ->label('Dinas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('layanan.nama_layanan')
                    ->label('Layanan')
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('nama_pemohon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_pendaftaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'online' => 'success',
                        'offline' => 'info',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'waiting' => 'gray',
                        'calling' => 'warning',
                        'serving' => 'info',
                        'skipped' => 'danger',
                        'completed' => 'success',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('loket_nomor')
                    ->label('Loket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu_ambil')
                    ->time('H:i')
                    ->label('Jam Ambil')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('dinas_id')
                    ->relationship('dinas', 'nama')
                    ->label('Gerai Dinas'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'waiting' => 'Menunggu',
                        'calling' => 'Dipanggil',
                        'serving' => 'Dilayani',
                        'skipped' => 'Dilewati',
                        'completed' => 'Selesai',
                    ]),
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal')
                            ->default(now()),
                    ])
                    ->query(fn ($query, array $data) => 
                        $query->when($data['tanggal'], fn ($q, $date) => $q->whereDate('tanggal', $date))
                    ),
            ])
            ->actions([
                // Call Action (Panggil)
                Action::make('panggil')
                    ->label('Panggil')
                    ->icon('heroicon-m-phone-arrow-up-right')
                    ->color('warning')
                    ->visible(fn (Antrian $record) => in_array($record->status, ['waiting', 'skipped']))
                    ->form([
                        Forms\Components\TextInput::make('loket_nomor')
                            ->label('Nomor Loket/Gerai')
                            ->required()
                            ->default(fn (Antrian $record) => $record->loket_nomor ?? 'Loket 1'),
                    ])
                    ->action(function (Antrian $record, array $data) {
                        $record->update([
                            'status' => 'calling',
                            'loket_nomor' => $data['loket_nomor'],
                            'waktu_panggil' => now(),
                        ]);
                        
                        // WhatsApp notification Simulation
                        // sendWhatsappNotification($record->nomor_hp, "Antrian Anda {$record->nomor_antrian} dipanggil di {$data['loket_nomor']}.");
                    }),

                // Serve Action (Mulai Layanan)
                Action::make('mulai')
                    ->label('Layani')
                    ->icon('heroicon-m-play')
                    ->color('info')
                    ->visible(fn (Antrian $record) => $record->status === 'calling')
                    ->action(function (Antrian $record) {
                        $record->update([
                            'status' => 'serving',
                            'waktu_mulai_layanan' => now(),
                        ]);
                    }),

                // Skip Action (Lewati)
                Action::make('lewati')
                    ->label('Lewati')
                    ->icon('heroicon-m-forward')
                    ->color('danger')
                    ->visible(fn (Antrian $record) => in_array($record->status, ['calling', 'serving']))
                    ->action(function (Antrian $record) {
                        $record->update([
                            'status' => 'skipped',
                        ]);
                    }),

                // Complete Action (Selesai & Buat Tracking)
                Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (Antrian $record) => $record->status === 'serving')
                    ->form([
                        Forms\Components\Toggle::make('buat_tracking')
                            ->label('Buat Berkas Lacak (Tracking Progress)?')
                            ->default(true)
                            ->live(),
                        Forms\Components\TextInput::make('nomor_lacak')
                            ->label('Nomor Lacak')
                            ->helperText('Otomatis digenerate jika kosong')
                            ->visible(fn (callable $get) => $get('buat_tracking')),
                    ])
                    ->action(function (Antrian $record, array $data) {
                        $record->update([
                            'status' => 'completed',
                            'waktu_selesai' => now(),
                        ]);

                        if ($data['buat_tracking'] && $record->layanan_id) {
                            $nomorLacak = $data['nomor_lacak'] ?: 'MPP-' . now()->format('Ymd') . '-' . str_pad($record->id, 4, '0', STR_PAD_LEFT);
                            
                            $tracking = Tracking::create([
                                'antrian_id' => $record->id,
                                'dinas_id' => $record->dinas_id,
                                'layanan_id' => $record->layanan_id,
                                'nomor_lacak' => $nomorLacak,
                                'nama_pemohon' => $record->nama_pemohon,
                                'nomor_hp' => $record->nomor_hp,
                                'status_sekarang' => 'submitted',
                                'catatan_terakhir' => 'Berkas pendaftaran diterima di loket.',
                                'tanggal_selesai_estimasi' => now()->addMinutes($record->layanan->estimasi_waktu ?? 60),
                            ]);

                            TrackingLog::create([
                                'tracking_id' => $tracking->id,
                                'status' => 'submitted',
                                'petugas_id' => auth()->id(),
                                'catatan' => 'Berkas pendaftaran diterima dan diproses di loket antrian.',
                            ]);
                        }
                    }),

                EditAction::make(),
            ])
            
           ->bulkActions([
    \Filament\Actions\BulkActionGroup::make([
        \Filament\Actions\DeleteBulkAction::make(),
    ]),

]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAntrian::route('/'),
        ];
    }
}
