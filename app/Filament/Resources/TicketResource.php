<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static BackedEnum | string | null $navigationIcon = 'heroicon-o-ticket';

    protected static UnitEnum | string | null $navigationGroup = 'Dane';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Tabs::make('Ticket')
                            ->tabs([
                                Tab::make('Ticket')
                                    ->schema([
                                        TextInput::make('ticket_id')
                                            ->label('Ticket ID')
                                            ->disabled()
                                            ->columnSpan(1),
                                        TextInput::make('status')
                                            ->label('Status')
                                            ->columnSpan(1),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->columnSpan(1),
                                        TextInput::make('title')
                                            ->label('Tytuł')
                                            ->columnSpan(3)
                                            ->columnStart(1),
                                        TextInput::make('via')
                                            ->label('Kanał')
                                            ->columnSpan(1)
                                            ->columnStart(1),
                                        TextInput::make('language')
                                            ->label('Język')
                                            ->columnSpan(1),
                                        TextInput::make('first_name')
                                            ->label('Imię')
                                            ->columnSpan(1),
                                        TextInput::make('name')
                                            ->label('Nazwisko')
                                            ->columnSpan(1)
                                            ->columnStart(1),
                                        TextInput::make('organization')
                                            ->label('Organizacja')
                                            ->columnSpan(2),
                                        Textarea::make('description')
                                            ->label('Treść')
                                            ->rows(8)
                                            ->columnSpan(3)
                                            ->columnStart(1),
                                    ])
                                    ->columns(3),

                                Tab::make('Contact')
                                    ->schema(function (Ticket $record): array {
                                        $contact = $record->contact;

                                        return [
                                            TextInput::make('contact_name')
                                                ->label('Kontakt')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $contact?->contact_name)
                                                ->columnSpan(1)
                                                ->columnStart(1),
                                            TextInput::make('contact_email')
                                                ->label('Email')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $contact?->contact_email)
                                                ->columnSpan(1),
                                            TextInput::make('contact_phone')
                                                ->label('Telefon')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $contact?->contact_phone)
                                                ->columnSpan(1),
                                            TextInput::make('contact_domain')
                                                ->label('Domena')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $contact?->contact_domain)
                                                ->columnSpan(1)
                                                ->columnStart(1),
                                            TextInput::make('company_name_external')
                                                ->label('Firma (nazwa zewnętrzna)')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $contact?->company_name)
                                                ->columnSpan(2),
                                        ];
                                    })
                                    ->columns(3),

                                Tab::make('Company')
                                    ->schema(function (Ticket $record): array {
                                        $company = $record->company;

                                        return [
                                            TextInput::make('company_label')
                                                ->label('Nazwa')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $company?->company_name)
                                                ->columnSpan(1)
                                                ->columnStart(1),
                                            TextInput::make('company_country')
                                                ->label('Kraj')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $company?->company_country)
                                                ->columnSpan(1),
                                            TextInput::make('company_city')
                                                ->label('Miasto')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $company?->company_city)
                                                ->columnSpan(1),
                                            TextInput::make('company_zip_code')
                                                ->label('Kod')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $company?->company_zip_code)
                                                ->columnSpan(1)
                                                ->columnStart(1),
                                            TextInput::make('company_address')
                                                ->label('Adres')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $company?->company_address)
                                                ->columnSpan(1),
                                            TextInput::make('company_vat')
                                                ->label('VAT ID')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $company?->company_VAT_ID)
                                                ->columnSpan(1),
                                            Textarea::make('company_description')
                                                ->label('Opis')
                                                ->rows(10)
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $company?->company_description)
                                                ->columnSpan(3)
                                                ->columnStart(1),
                                        ];
                                    })
                                    ->columns(3),
                            ]),
                    ])
                    ->columns(1)
                    ->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('ticket_id')
                    ->label('Ticket ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Tytuł')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->title)
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => fn ($state) => str($state)->lower()->contains('now'),
                        'success' => fn ($state) => str($state)->lower()->contains('zam'),
                        'info' => fn ($state) => str($state)->lower()->contains('w toku'),
                        'secondary',
                    ]),
                TextColumn::make('company.company_name')
                    ->label('Firma')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('contact.contact_name')
                    ->label('Kontakt')
                    ->toggleable(),
                TextColumn::make('via')
                    ->label('Kanał')
                    ->toggleable(),
                TextColumn::make('description')
                    ->label('Treść')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('language')
                    ->label('Język')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([]);
    }

    /**
     * @return array<class-string>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
