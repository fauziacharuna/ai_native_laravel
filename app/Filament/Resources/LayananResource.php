<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LayananResource\Pages;
use App\Models\Layanan;
use Filament\Forms;
use Filament\Schemas\Schema;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LayananResource extends Resource
{
    protected static ?string $model = Layanan::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Standar Pelayanan';
    protected static ?string $modelLabel = 'Standar Pelayanan';
    protected static ?string $pluralModelLabel = 'Standar Pelayanan';
    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    public static function Schema(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dinas_id')
                    ->relationship('dinas', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('nama_layanan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('persyaratan')
                    ->rows(5)
                    ->placeholder("Daftar dokumen persyaratan...")
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('prosedur')
                    ->rows(5)
                    ->placeholder("Langkah-langkah pengajuan...")
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('estimasi_waktu')
                    ->required()
                    ->numeric()
                    ->suffix(' Menit')
                    ->default(60),
                Forms\Components\TextInput::make('tarif')
                    ->required()
                    ->numeric()
                    ->prefix('Rp ')
                    ->default(0),
                Forms\Components\TextInput::make('produk')
                    ->placeholder('e.g. KTP-el Fisik, Sertifikat')
                    ->maxLength(255),
                Forms\Components\Textarea::make('pengaduan')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('status')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dinas.kode')
                    ->label('Dinas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_layanan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimasi_waktu')
                    ->numeric()
                    ->suffix(' Menit')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tarif')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('produk')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('dinas_id')
                    ->relationship('dinas', 'nama')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLayanans::route('/'),
        ];
    }
}
