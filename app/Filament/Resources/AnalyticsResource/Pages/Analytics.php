<?php

namespace App\Filament\Resources\AnalyticsResource\Pages;

use App\Filament\Resources\AnalyticsResource;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Resources\Pages\Page;

class Analytics extends Page
{
    use InteractsWithHeaderActions;

    protected static string $resource = AnalyticsResource::class;

    protected static ?string $title = 'Analizy';

    protected string $view = 'filament.resources.analytics-resource.pages.analytics';

    protected function getHeaderWidgets(): array
    {
        return [
            AnalyticsResource\Widgets\FiltersForm::class,
            AnalyticsResource\Widgets\StatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AnalyticsResource\Widgets\CompaniesByCountryChart::class,
            AnalyticsResource\Widgets\TicketsTrendChart::class,
        ];
    }
}
