<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\Contact;
use App\Models\History;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Tickety', Ticket::count()),
            Stat::make('Kontakty', Contact::count()),
            Stat::make('Firmy', Company::count()),
            Stat::make('Historia operacji', History::count()),
        ];
    }
}
