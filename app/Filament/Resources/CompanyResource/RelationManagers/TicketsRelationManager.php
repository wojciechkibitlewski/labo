<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $domain = $this->ownerRecord->domain;
                $contactEmails = $this->ownerRecord->contacts()
                    ->pluck('contact_email')
                    ->filter()
                    ->all();

                $query->where('company_id', $this->ownerRecord->id);

                if ($domain) {
                    $query->orWhere('email', 'like', '%@' . $domain);
                }

                if (! empty($contactEmails)) {
                    $query->orWhereIn('email', $contactEmails);
                }
            })
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('ticket_id')
                    ->label('Ticket ID')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Tytuł')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->title),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Treść')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime('Y-m-d H:i'),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                ViewAction::make()
                    ->form([
                        TextInput::make('ticket_id')
                            ->label('Ticket ID')
                            ->disabled(),
                        TextInput::make('title')
                            ->label('Tytuł')
                            ->disabled(),
                        TextInput::make('status')
                            ->label('Status')
                            ->disabled(),
                        TextInput::make('email')
                            ->label('Email')
                            ->disabled(),
                        Textarea::make('description')
                            ->label('Treść')
                            ->rows(8)
                            ->disabled(),
                    ]),
            ])
            ->bulkActions([]);
    }
}
