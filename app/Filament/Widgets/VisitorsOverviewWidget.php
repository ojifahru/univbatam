<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class VisitorsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // Pengunjung hari ini
        $visitorsToday = Visitor::whereDate('created_at', Carbon::today())->count();
        $uniqueVisitorsToday = Visitor::whereDate('created_at', Carbon::today())
            ->distinct('ip_address')
            ->count('ip_address');
            
        // Pengunjung kemarin untuk perbandingan
        $visitorsYesterday = Visitor::whereDate('created_at', Carbon::yesterday())->count();
        $uniqueVisitorsYesterday = Visitor::whereDate('created_at', Carbon::yesterday())
            ->distinct('ip_address')
            ->count('ip_address');
            
        // Pengunjung minggu ini
        $startOfWeek = Carbon::now()->startOfWeek();
        $visitorsThisWeek = Visitor::where('created_at', '>=', $startOfWeek)->count();
        $uniqueVisitorsThisWeek = Visitor::where('created_at', '>=', $startOfWeek)
            ->distinct('ip_address')
            ->count('ip_address');
            
        // Hitung persentase perubahan
        $visitorChange = ($visitorsYesterday > 0) 
            ? round((($visitorsToday - $visitorsYesterday) / $visitorsYesterday) * 100) 
            : 0;
        
        $uniqueVisitorChange = ($uniqueVisitorsYesterday > 0)
            ? round((($uniqueVisitorsToday - $uniqueVisitorsYesterday) / $uniqueVisitorsYesterday) * 100)
            : 0;

        return [
            Stat::make('Pengunjung Hari Ini', $visitorsToday)
                ->description($visitorChange >= 0 ? "+ {$visitorChange}% dari kemarin" : "- " . abs($visitorChange) . "% dari kemarin")
                ->descriptionIcon($visitorChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($visitorChange >= 0 ? 'success' : 'danger')
                ->chart([
                    Visitor::whereDate('created_at', Carbon::today()->subDays(6))->count(),
                    Visitor::whereDate('created_at', Carbon::today()->subDays(5))->count(),
                    Visitor::whereDate('created_at', Carbon::today()->subDays(4))->count(),
                    Visitor::whereDate('created_at', Carbon::today()->subDays(3))->count(),
                    Visitor::whereDate('created_at', Carbon::today()->subDays(2))->count(),
                    Visitor::whereDate('created_at', Carbon::today()->subDays(1))->count(),
                    $visitorsToday,
                ]),

            Stat::make('Pengunjung Unik Hari Ini', $uniqueVisitorsToday)
                ->description($uniqueVisitorChange >= 0 ? "+ {$uniqueVisitorChange}% dari kemarin" : "- " . abs($uniqueVisitorChange) . "% dari kemarin")
                ->descriptionIcon($uniqueVisitorChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($uniqueVisitorChange >= 0 ? 'success' : 'danger'),

            Stat::make('Total Minggu Ini', $visitorsThisWeek)
                ->description("{$uniqueVisitorsThisWeek} pengunjung unik minggu ini")
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
