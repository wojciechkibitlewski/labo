<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $domain = $this->ownerRecord->domain;

                $query->where('company_id', $this->ownerRecord->id);

                if ($domain) {
                    $query->orWhere('contact_domain', $domain)
                        ->orWhere('contact_email', 'like', '%@' . $domain);
                }
            })
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('contact_name')
                    ->label('Kontakt')
                    ->searchable(),
                TextColumn::make('contact_email')
                    ->label('Email')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('contact_phone')
                    ->label('Telefon')
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->contact_phone),
                TextColumn::make('created_at')
                    ->label('Dodano')
                    ->dateTime('Y-m-d H:i'),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
