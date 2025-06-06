<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class BotStatisticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Statistik Bot/Crawler';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    // Filter periode
    public ?string $filter = '7d';

    public function getFilters(): ?array
    {
        return [
            '7d' => '7 hari terakhir',
            '30d' => '30 hari terakhir',
            'all' => 'Semua waktu',
        ];
    }

    protected function getData(): array
    {
        $days = match ($this->filter) {
            '7d' => 7,
            '30d' => 30,
            'all' => 365, // Batasi maksimal 1 tahun
        };

        $botStats = Visitor::getBotStatistics($days, 10);

        // Jika tidak ada data
        if (empty($botStats)) {
            return [
                'datasets' => [
                    [
                        'label' => 'Jumlah Kunjungan',
                        'data' => [],
                        'backgroundColor' => [
                            '#f87171',
                            '#fb923c',
                            '#fbbf24',
                            '#a3e635',
                            '#34d399',
                            '#2dd4bf',
                            '#38bdf8',
                            '#818cf8',
                            '#c084fc',
                            '#e879f9'
                        ],
                    ],
                ],
                'labels' => [],
            ];
        }

        // Extrak data untuk chart
        $labels = array_column($botStats, 'bot_name');
        $data = array_column($botStats, 'total');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kunjungan',
                    'data' => $data,
                    'backgroundColor' => [
                        '#f87171',
                        '#fb923c',
                        '#fbbf24',
                        '#a3e635',
                        '#34d399',
                        '#2dd4bf',
                        '#38bdf8',
                        '#818cf8',
                        '#c084fc',
                        '#e879f9'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'right',
                ],
                'tooltip' => [
                    'mode' => 'index',
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
