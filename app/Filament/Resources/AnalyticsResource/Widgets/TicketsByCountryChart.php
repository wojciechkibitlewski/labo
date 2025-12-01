<?php

namespace App\Filament\Resources\AnalyticsResource\Widgets;

use App\Models\Ticket;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Livewire\Attributes\On;

class TicketsByCountryChart extends ApexChartWidget
{
    protected static ?string $chartId = 'ticketsByCountryChart';

    protected static ?string $heading = 'Liczba ticketów';

    protected static ?int $contentHeight = 300;

    public ?string $filter = 'last_week';

    #[On('filterChanged')]
    public function updateFilter($period, $startDate, $endDate): void
    {
        $this->filter = json_encode(compact('period', 'startDate', 'endDate'));
        $this->updateChartData();
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

    protected function getOptions(): array
    {
        $dateRange = $this->getDateRange();

        $tickets = Ticket::whereBetween('Tickets.created_at', [$dateRange['start'], $dateRange['end']])
            ->join('Companies', 'Tickets.company_id', '=', 'Companies.id')
            ->selectRaw('Companies.company_country, COUNT(*) as count')
            ->groupBy('Companies.company_country')
            ->orderBy('count', 'desc')
            ->get();

        $data = [];
        $categories = [];

        foreach ($tickets as $ticket) {
            $categories[] = $ticket->company_country ?: 'Nieznany';
            $data[] = $ticket->count;
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Liczba ticketów',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => $categories,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#22c55e'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                ],
            ],
        ];
    }
}
