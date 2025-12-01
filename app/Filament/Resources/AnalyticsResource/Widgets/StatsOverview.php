<?php

namespace App\Filament\Resources\AnalyticsResource\Widgets;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;

class StatsOverview extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'last_week';

    #[On('filterChanged')]
    public function updateFilter($period, $startDate, $endDate): void
    {
        $this->filter = json_encode(compact('period', 'startDate', 'endDate'));
        // Livewire automatycznie odświeży widget
    }

    protected function getDateRange(): array
    {
        if ($this->filter && $this->filter !== 'last_week') {
            $filterData = json_decode($this->filter, true);

            if (isset($filterData['startDate']) && isset($filterData['endDate']) && $filterData['startDate'] && $filterData['endDate']) {
                return [
                    'start' => Carbon::parse($filterData['startDate'])->startOfDay(),
                    'end' => Carbon::parse($filterData['endDate'])->endOfDay(),
                ];
            }

            $period = $filterData['period'] ?? 'last_week';
        } else {
            $period = 'last_week';
        }

        $end = Carbon::now();
        $start = match ($period) {
            'last_week' => Carbon::now()->subWeek(),
            'last_month' => Carbon::now()->subMonth(),
            'last_year' => Carbon::now()->subYear(),
            default => Carbon::now()->subWeek(),
        };

        return ['start' => $start, 'end' => $end];
    }

    protected function getStats(): array
    {
        $dateRange = $this->getDateRange();

        return [
            Stat::make('Liczba ticketów', Ticket::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count()),
            Stat::make('Liczba kontaktów', Contact::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count()),
            Stat::make('Liczba firm', Company::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count()),
        ];
    }
}
