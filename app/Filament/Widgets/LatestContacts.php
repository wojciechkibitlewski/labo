<?php

namespace App\Filament\Widgets;

use App\Models\Contact;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestContacts extends TableWidget
{
    protected static ?string $heading = 'Ostatnie kontakty';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Contact::query()->latest('created_at')
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('contact_name')
                    ->label('Kontakt')
                    ->searchable(),
                TextColumn::make('contact_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('contact_phone')
                    ->label('Telefon')
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->contact_phone),
                TextColumn::make('company.company_name')
                    ->label('Firma')
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Dodano')
                    ->dateTime('Y-m-d H:i'),
            ]);
    }
}
