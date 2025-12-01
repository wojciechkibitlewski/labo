<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LatestCompanies;
use App\Filament\Widgets\LatestContacts;
use App\Filament\Widgets\LatestHistory;
use App\Filament\Widgets\LatestTickets;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            LatestTickets::class,
            LatestContacts::class,
            LatestCompanies::class,
            LatestHistory::class,
        ];
    }

    public function getColumns(): int | array
    {
        return [
            'sm' => 1,
            'lg' => 2,
        ];
    }
}
