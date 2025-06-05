<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Visitor;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class VisitorsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Statistik Pengunjung';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        try {
            $startDate = now()->subDays(30);
            $endDate = now();

            // Data pengunjung harian untuk 30 hari terakhir
            $data = Trend::model(Visitor::class)
                ->between(
                    start: $startDate,
                    end: $endDate
                )
                ->perDay()
                ->count();

            // Data pengunjung unik (berdasarkan IP)
            // Perbaikan: tambahkan between() sebelum count()
            $uniqueData = Trend::query(
                Visitor::query()->select(DB::raw('COUNT(DISTINCT ip_address) as count'), DB::raw('DATE(created_at) as date'))
                    ->where('created_at', '>=', $startDate)
                    ->where('created_at', '<=', $endDate)
                    ->groupBy('date')
            )
                ->dateColumn('date')
                ->between(
                    start: $startDate,
                    end: $endDate
                )
                ->perDay()
                ->aggregate('count', 'count'); // Gunakan aggregate() daripada count('count')

            // Cek apakah data kosong
            if ($data->isEmpty()) {
                return [
                    'datasets' => [
                        [
                            'label' => 'Total Pengunjung',
                            'data' => [],
                            'backgroundColor' => '#f59e0b',
                            'borderColor' => '#f59e0b',
                        ],
                        [
                            'label' => 'Pengunjung Unik',
                            'data' => [],
                            'backgroundColor' => '#3b82f6',
                            'borderColor' => '#3b82f6',
                        ],
                    ],
                    'labels' => [],
                ];
            }

            // Tampilkan hanya tanggal (tanpa tahun jika tahun saat ini)
            $currentYear = now()->year;
            $labels = $data->map(function (TrendValue $value) use ($currentYear) {
                $date = Carbon::parse($value->date);
                return ($date->year == $currentYear)
                    ? $date->format('d M')
                    : $date->format('d M Y');
            });

            return [
                'datasets' => [
                    [
                        'label' => 'Total Pengunjung',
                        'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                        'backgroundColor' => '#f59e0b',
                        'borderColor' => '#f59e0b',
                    ],
                    [
                        'label' => 'Pengunjung Unik',
                        'data' => $uniqueData->map(fn(TrendValue $value) => $value->aggregate),
                        'backgroundColor' => '#3b82f6',
                        'borderColor' => '#3b82f6',
                    ],
                ],
                'labels' => $labels,
            ];
        } catch (\Exception $e) {
            // Tangani error dan berikan data default
            \Log::error('Error in VisitorsChartWidget: ' . $e->getMessage());

            return [
                'datasets' => [
                    [
                        'label' => 'Total Pengunjung',
                        'data' => [],
                        'backgroundColor' => '#f59e0b',
                        'borderColor' => '#f59e0b',
                    ],
                    [
                        'label' => 'Pengunjung Unik',
                        'data' => [],
                        'backgroundColor' => '#3b82f6',
                        'borderColor' => '#3b82f6',
                    ],
                ],
                'labels' => [],
            ];
        }
    }

    protected function getType(): string
    {
        return 'line';
    }

    // Opsi tambahan untuk chart
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0, // Hanya tampilkan bilangan bulat
                    ],
                ],
            ],
        ];
    }
}
