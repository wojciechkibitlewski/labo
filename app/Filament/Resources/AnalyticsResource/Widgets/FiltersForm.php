<?php

namespace App\Filament\Resources\AnalyticsResource\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class FiltersForm extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.resources.analytics-resource.widgets.filters-form';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'period' => 'last_week',
            'startDate' => null,
            'endDate' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('period')
                    ->label('Okres')
                    ->options([
                        'last_week' => 'Ostatni tydzień',
                        'last_month' => 'Ostatni miesiąc',
                        'last_year' => 'Ostatni rok',
                    ])
                    ->default('last_week')
                    ->live()
                    ->afterStateUpdated(fn () => $this->dispatchFilters()),
                DatePicker::make('startDate')
                    ->label('Data początkowa')
                    ->live()
                    ->afterStateUpdated(fn () => $this->dispatchFilters()),
                DatePicker::make('endDate')
                    ->label('Data końcowa')
                    ->live()
                    ->afterStateUpdated(fn () => $this->dispatchFilters()),
            ])
            ->statePath('data')
            ->columns(3);
    }

    protected function dispatchFilters(): void
    {
        $this->dispatch('filterChanged',
            period: $this->data['period'] ?? 'last_week',
            startDate: $this->data['startDate'] ?? null,
            endDate: $this->data['endDate'] ?? null
        );
    }
}
