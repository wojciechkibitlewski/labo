<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestTickets extends TableWidget
{
    protected static ?string $heading = 'Ostatnie tickety';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ticket::query()->latest('created_at')
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('ticket_id')
                    ->label('Ticket ID')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Tytuł')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->title),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('company.company_name')
                    ->label('Firma')
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime('Y-m-d H:i'),
            ]);
    }
}
