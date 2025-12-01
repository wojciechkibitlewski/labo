<?php

namespace App\Filament\Resources\AnalyticsResource\Widgets;

use App\Models\Ticket;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Livewire\Attributes\On;

class TicketsTrendChart extends ApexChartWidget
{
    protected static ?string $chartId = 'ticketsTrendChart';

    protected static ?string $heading = 'Trend ticketów w czasie';

    protected static ?int $contentHeight = 300;

    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'last_week';

    #[On('filterChanged')]
    public function updateFilter($period, $startDate, $endDate): void
    {
        $this->filter = json_encode(compact('period', 'startDate', 'endDate'));
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
            ->selectRaw('DATE(Tickets.created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $data = [];
        $categories = [];

        foreach ($tickets as $ticket) {
            $categories[] = Carbon::parse($ticket->date)->format('d.m.Y');
            $data[] = $ticket->count;
        }

        return [
            'chart' => [
                'type' => 'area',
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
            'colors' => ['#3b82f6'],
            'stroke' => [
                'curve' => 'smooth',
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
        ];
    }
}
