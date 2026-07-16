<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DinasResource\Pages;
use App\Models\Dinas;
use Filament\Forms;
use Filament\Schemas\Schema;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DinasResource extends Resource
{
    protected static ?string $model = Dinas::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Gerai Dinas';
    protected static ?string $modelLabel = 'Gerai Dinas';
    protected static ?string $pluralModelLabel = 'Gerai Dinas';
    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    public static function Schema(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kode')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
                Forms\Components\Textarea::make('deskripsi')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('jam_operasional')
                    ->placeholder('e.g. 08:00 - 15:00')
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_operasional')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Non-Aktif',
                    ]),
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
            'index' => Pages\ManageDinas::route('/'),
        ];
    }
}
