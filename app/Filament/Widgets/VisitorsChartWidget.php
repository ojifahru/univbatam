<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Visitor;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;

class VisitorsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Statistik Pengunjung';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';
    
    // Filter periode
    public ?string $filter = '30d';
    
    public function getFilters(): ?array
    {
        return [
            'today' => 'Hari ini',
            '7d' => '7 hari terakhir',
            '30d' => '30 hari terakhir',
            'month' => 'Bulan ini',
            'year' => 'Tahun ini',
        ];
    }

    protected function getData(): array
    {
        try {
            // Set rentang tanggal berdasarkan filter yang dipilih
            $startDate = match($this->filter) {
                'today' => now()->startOfDay(),
                '7d' => now()->subDays(7)->startOfDay(),
                '30d' => now()->subDays(30)->startOfDay(),
                'month' => now()->startOfMonth(),
                'year' => now()->startOfYear(),
                default => now()->subDays(30)->startOfDay(),
            };
            $endDate = now();
            
            // Tentukan interval berdasarkan filter
            $interval = match($this->filter) {
                'today' => 'perHour',
                '7d' => 'perDay',
                '30d' => 'perDay',
                'month' => 'perDay',
                'year' => 'perMonth',
                default => 'perDay',
            };
            
            // Jika filter hari ini, gunakan tampilan per jam
            if ($this->filter === 'today') {
                return $this->getHourlyChartData();
            }
            
            // Data pengunjung sesuai interval yang dipilih
            $data = Trend::model(Visitor::class)
                ->between(
                    start: $startDate,
                    end: $endDate
                )
                ->$interval() // Menggunakan interval yang dinamis
                ->count();

            // Data pengunjung unik (berdasarkan IP)
            $uniqueData = Trend::query(
                Visitor::query()->select(DB::raw('COUNT(DISTINCT ip_address) as count'), 
                                         DB::raw($this->getSqlDateFormat($this->filter)))
                    ->where('created_at', '>=', $startDate)
                    ->where('created_at', '<=', $endDate)
                    ->groupBy('date')
            )
                ->dateColumn('date')
                ->between(
                    start: $startDate,
                    end: $endDate
                )
                ->$interval()
                ->aggregate('count', 'count');

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

            // Format label sesuai dengan interval yang dipilih
            $labels = $data->map(function (TrendValue $value) {
                return $this->formatLabel($value->date, $this->filter);
            });

            return [
                'datasets' => [
                    [
                        'label' => 'Total Pengunjung',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                        'backgroundColor' => '#f59e0b',
                        'borderColor' => '#f59e0b',
                    ],
                    [
                        'label' => 'Pengunjung Unik',
                        'data' => $uniqueData->map(fn (TrendValue $value) => $value->aggregate),
                        'backgroundColor' => '#3b82f6',
                        'borderColor' => '#3b82f6',
                    ],
                ],
                'labels' => $labels,
            ];
        } catch (\Exception $e) {
            \Log::error('Error in VisitorsChartWidget: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            
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
    
    /**
     * Format SQL untuk mendapatkan kolom date berdasarkan filter
     */
    protected function getSqlDateFormat(string $filter): string
    {
        return match($filter) {
            'today' => "DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') as date",
            'year' => "DATE_FORMAT(created_at, '%Y-%m-01') as date",
            default => "DATE(created_at) as date",
        };
    }
    
    /**
     * Format label tanggal berdasarkan filter
     */
    protected function formatLabel(string $date, string $filter): string
    {
        $parsedDate = Carbon::parse($date);
        
        return match($filter) {
            'today' => $parsedDate->format('H:i'),
            '7d', '30d', 'month' => $parsedDate->format('d M'),
            'year' => $parsedDate->format('M Y'),
            default => $parsedDate->format('d M'),
        };
    }
    
    /**
     * Dapatkan data chart per jam untuk hari ini
     */
    protected function getHourlyChartData(): array
    {
        $today = now()->startOfDay();
        $currentHour = now()->hour;
        $hours = [];
        $visitorData = [];
        $uniqueIps = [];
        
        // Buat array untuk jam hari ini (hingga jam saat ini)
        for ($i = 0; $i <= $currentHour; $i++) {
            $hour = $today->copy()->addHours($i);
            $hours[] = $hour->format('H:i');
            
            // Ambil data untuk jam ini
            $startHour = $today->copy()->addHours($i);
            $endHour = $today->copy()->addHours($i + 1);
            
            $visitors = Visitor::whereBetween('created_at', [$startHour, $endHour])->get();
            
            $visitorData[] = $visitors->count();
            $uniqueIps[] = $visitors->pluck('ip_address')->unique()->count();
        }
        
        // Tambahkan jam-jam yang tersisa dengan nilai nol
        for ($i = $currentHour + 1; $i < 24; $i++) {
            $hour = $today->copy()->addHours($i);
            $hours[] = $hour->format('H:i');
            $visitorData[] = null; // Menggunakan null daripada 0 agar tidak muncul di chart
            $uniqueIps[] = null; // Menggunakan null daripada 0 agar tidak muncul di chart
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Total Pengunjung Hari Ini',
                    'data' => $visitorData,
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#f59e0b',
                    'spanGaps' => true, // Akan menggambar garis meskipun ada nilai null
                ],
                [
                    'label' => 'Pengunjung Unik Hari Ini',
                    'data' => $uniqueIps,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#3b82f6',
                    'spanGaps' => true, // Akan menggambar garis meskipun ada nilai null
                ],
            ],
            'labels' => $hours,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0, // Hanya tampilkan bilangan bulat
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'elements' => [
                'line' => [
                    'tension' => 0.3, // Sedikit lengkungan pada garis
                ],
                'point' => [
                    'radius' => 3,
                    'hoverRadius' => 5,
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
