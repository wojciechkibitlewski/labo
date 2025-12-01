<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use UnitEnum;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static BackedEnum | string | null $navigationIcon = 'heroicon-o-user';

    protected static UnitEnum | string | null $navigationGroup = 'Dane';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Tabs::make('Kontakt')
                            ->tabs([
                                Tab::make('Kontakt')
                                    ->schema([
                                        TextInput::make('contact_name')
                                            ->label('Kontakt')
                                            ->columnSpan(1),
                                        TextInput::make('contact_email')
                                            ->label('Email')
                                            ->columnSpan(1),
                                        TextInput::make('contact_phone')
                                            ->label('Telefon')
                                            ->columnSpan(1),
                                        TextInput::make('contact_domain')
                                            ->label('Domena')
                                            ->columnSpan(1),
                                        TextInput::make('company_name')
                                            ->label('Firma (nazwa zewnętrzna)')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                
                                Tab::make('Firma')
                                    ->schema([
                                        TextInput::make('company_label')
                                            ->label('Nazwa')
                                            ->dehydrated(false)
                                            ->formatStateUsing(fn (Contact $record) => $record->company?->company_name ?? $record->company_name)
                                            ->columnSpan(1)
                                            ->columnStart(1),
                                        TextInput::make('company_country')
                                            ->label('Kraj')
                                            ->dehydrated(false)
                                            ->formatStateUsing(fn (Contact $record) => $record->company?->company_country)
                                            ->columnSpan(1),
                                        TextInput::make('company_city')
                                            ->label('Miasto')
                                            ->dehydrated(false)
                                            ->formatStateUsing(fn (Contact $record) => $record->company?->company_city)
                                            ->columnSpan(1),
                                        TextInput::make('company_zip_code')
                                            ->label('Kod')
                                            ->dehydrated(false)
                                            ->formatStateUsing(fn (Contact $record) => $record->company?->company_zip_code)
                                            ->columnSpan(1)
                                            ->columnStart(1),
                                        TextInput::make('company_address')
                                            ->label('Adres')
                                            ->dehydrated(false)
                                            ->formatStateUsing(fn (Contact $record) => $record->company?->company_address)
                                            ->columnSpan(1),
                                        TextInput::make('company_vat')
                                            ->label('VAT ID')
                                            ->dehydrated(false)
                                            ->formatStateUsing(fn (Contact $record) => $record->company?->company_VAT_ID)
                                            ->columnSpan(1),
                                        Textarea::make('company_description')
                                            ->label('Opis')
                                            ->rows(10)
                                            ->dehydrated(false)
                                            ->formatStateUsing(fn (Contact $record) => $record->company?->company_description)
                                            ->columnSpan(3)
                                            ->columnStart(1),
                                        
                                    ])
                                    ->columns(3),
                                Tab::make('Ticket')
                                    ->schema(function (Contact $record): array {
                                        $ticket = $record->primaryTicket()->first() ?? $record->ticketByEmail()->first();

                                        return [
                                            TextInput::make('ticket_id')
                                                ->label('Ticket ID')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $ticket?->ticket_id)
                                                ->columnSpan(1)
                                                ->columnStart(1),
                                            TextInput::make('ticket_status')
                                                ->label('Status')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $ticket?->status)
                                                ->columnSpan(1),
                                            TextInput::make('ticket_email')
                                                ->label('Email')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $ticket?->email)
                                                ->columnSpan(1),
                                            TextInput::make('ticket_title')
                                                ->label('Tytuł')
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $ticket?->title)
                                                ->columnSpan(3)
                                                ->columnStart(1),
                                            
                                            
                                            Textarea::make('ticket_description')
                                                ->label('Treść')
                                                ->rows(8)
                                                ->dehydrated(false)
                                                ->formatStateUsing(fn () => $ticket?->description)
                                                ->columnSpan(3),
                                            
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
                TextColumn::make('contact_name')
                    ->label('Kontakt')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contact_email')
                    ->label('Email')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('contact_phone')
                    ->label('Telefon')
                    ->toggleable(),
                TextColumn::make('company.company_name')
                    ->label('Firma')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Dodano')
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
            'index' => Pages\ListContacts::route('/'),
            'view' => Pages\ViewContact::route('/{record}'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
