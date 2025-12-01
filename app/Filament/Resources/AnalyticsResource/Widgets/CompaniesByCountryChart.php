<?php

namespace App\Filament\Resources\AnalyticsResource\Widgets;

use App\Models\Company;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class CompaniesByCountryChart extends ApexChartWidget
{
    protected static ?string $chartId = 'companiesByCountryChart';

    protected static ?string $heading = 'Firmy według kraju';

    protected static ?int $contentHeight = 300;

    protected int | string | array $columnSpan = 'full';

    protected function getOptions(): array
    {
        // Pobierz wszystkie firmy według kraju
        $companies = Company::selectRaw('company_country, COUNT(*) as count')
            ->whereNotNull('company_country')
            ->where('company_country', '!=', '')
            ->groupBy('company_country')
            ->orderBy('count', 'desc')
            ->get();

        $data = [];
        $categories = [];

        foreach ($companies as $company) {
            $categories[] = $company->company_country ?: 'Nieznany';
            $data[] = $company->count;
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Liczba firm',
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
            'colors' => ['#f59e0b'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                ],
            ],
        ];
    }
}
