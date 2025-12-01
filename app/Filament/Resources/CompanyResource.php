<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers\ContactsRelationManager;
use App\Filament\Resources\CompanyResource\RelationManagers\TicketsRelationManager;
use App\Models\Company;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static BackedEnum | string | null $navigationIcon = 'heroicon-o-building-office-2';

    protected static UnitEnum | string | null $navigationGroup = 'Dane';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Tabs::make('Firma')
                            ->tabs([
                                Tab::make('Firma')
                                    ->schema([
                                        TextInput::make('company_name')
                                            ->label('Nazwa')
                                            ->required()
                                            ->columnSpan(1),
                                        TextInput::make('company_country')
                                            ->label('Kraj')
                                            ->columnSpan(1),
                                        TextInput::make('company_region')
                                            ->label('Region')
                                            ->columnSpan(1),
                                        TextInput::make('company_city')
                                            ->label('Miasto')
                                            ->columnSpan(1)
                                            ->columnStart(1),
                                        TextInput::make('company_zip_code')
                                            ->label('Kod pocztowy')
                                            ->columnSpan(1),
                                        TextInput::make('company_address')
                                            ->label('Adres')
                                            ->columnSpan(1),
                                        TextInput::make('company_VAT_ID')
                                            ->label('VAT ID')
                                            ->columnSpan(1)
                                            ->columnStart(1),
                                        TextInput::make('domain')
                                            ->label('Domena')
                                            ->columnSpan(1),
                                        Textarea::make('company_description')
                                            ->label('Opis')
                                            ->rows(10)
                                            ->columnSpan(3)
                                            ->columnStart(1),
                                    ])
                                    ->columns(3),

                                Tab::make('Contact')
                                    ->schema(function (Company $record): array {
                                        $contacts = $record->contacts;

                                        if ($contacts->isEmpty()) {
                                            return [
                                                Section::make()
                                                    ->schema([
                                                        TextInput::make('no_contacts')
                                                            ->label('Brak kontaktów')
                                                            ->dehydrated(false)
                                                            ->disabled(),
                                                    ])
                                            ];
                                        }

                                        $fields = [];
                                        foreach ($contacts as $index => $contact) {
                                            $fields[] = Section::make('Kontakt ' . ($index + 1))
                                                ->schema([
                                                    TextInput::make("contact_name_{$index}")
                                                        ->label('Kontakt')
                                                        ->dehydrated(false)
                                                        ->formatStateUsing(fn () => $contact->contact_name)
                                                        ->columnSpan(1),
                                                    TextInput::make("contact_email_{$index}")
                                                        ->label('Email')
                                                        ->dehydrated(false)
                                                        ->formatStateUsing(fn () => $contact->contact_email)
                                                        ->columnSpan(1),
                                                    TextInput::make("contact_phone_{$index}")
                                                        ->label('Telefon')
                                                        ->dehydrated(false)
                                                        ->formatStateUsing(fn () => $contact->contact_phone)
                                                        ->columnSpan(1),
                                                    TextInput::make("contact_domain_{$index}")
                                                        ->label('Domena')
                                                        ->dehydrated(false)
                                                        ->formatStateUsing(fn () => $contact->contact_domain)
                                                        ->columnSpan(1),
                                                ])
                                                ->columns(2)
                                                ->collapsible();
                                        }

                                        return $fields;
                                    }),

                                Tab::make('Ticket')
                                    ->schema(function (Company $record): array {
                                        $ticket = $record->tickets()->first();

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
                TextColumn::make('company_name')
                    ->label('Nazwa')
                    ->searchable()
                    ->sortable()
                    ->url(fn (Company $record) => static::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(false),
                TextColumn::make('domain')
                    ->label('Domena')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->domain),
                TextColumn::make('company_country')
                    ->label('Kraj')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('company_city')
                    ->label('Miasto')
                    ->toggleable(),
                TextColumn::make('company_zip_code')
                    ->label('Kod')
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
            'index' => Pages\ListCompanies::route('/'),
            'view' => Pages\ViewCompany::route('/{record}'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<class-string>
     */
    public static function getRelations(): array
    {
        return [
            ContactsRelationManager::class,
            TicketsRelationManager::class,
        ];
    }
}
