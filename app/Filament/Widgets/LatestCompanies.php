<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestCompanies extends TableWidget
{
    protected static ?string $heading = 'Ostatnie firmy';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Company::query()->latest('created_at')
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('company_name')
                    ->label('Nazwa')
                    ->searchable(),
                TextColumn::make('domain')
                    ->label('Domena')
                    ->limit(30),
                TextColumn::make('company_country')
                    ->label('Kraj')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dodano')
                    ->dateTime('Y-m-d H:i'),
            ]);
    }
}
