<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoryResource\Pages;
use App\Models\History;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class HistoryResource extends Resource
{
    protected static ?string $model = History::class;

    protected static BackedEnum | string | null $navigationIcon = 'heroicon-o-clock';

    protected static UnitEnum | string | null $navigationGroup = 'Dane';

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('label')
                    ->label('Operacja')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('user')
                    ->label('Użytkownik')
                    ->toggleable(),
                TextColumn::make('ticket_id')
                    ->label('Ticket')
                    ->toggleable(),
                TextColumn::make('company_id')
                    ->label('Firma ID')
                    ->toggleable(),
                TextColumn::make('contact_id')
                    ->label('Kontakt ID')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Wykonano')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([])
            ->bulkActions([]);
    }

    /**
     * @return array<class-string>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHistories::route('/'),
        ];
    }
}
