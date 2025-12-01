<?php

namespace App\Filament\Widgets;

use App\Models\History;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestHistory extends TableWidget
{
    protected static ?string $heading = 'Historia operacji';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                History::query()->latest('created_at')
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('label')
                    ->label('Operacja')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('user')
                    ->label('Użytkownik'),
                TextColumn::make('ticket_id')
                    ->label('Ticket'),
                TextColumn::make('company_id')
                    ->label('Firma'),
                TextColumn::make('created_at')
                    ->label('Wykonano')
                    ->dateTime('Y-m-d H:i'),
            ]);
    }
}
