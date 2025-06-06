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
        // Pengunjung manusia hari ini
        $visitorsToday = Visitor::whereDate('created_at', Carbon::today())
            ->humansOnly()
            ->count();
            
        $uniqueVisitorsToday = Visitor::whereDate('created_at', Carbon::today())
            ->humansOnly()
            ->distinct('ip_address')
            ->count('ip_address');
            
        // Pengunjung manusia kemarin untuk perbandingan
        $visitorsYesterday = Visitor::whereDate('created_at', Carbon::yesterday())
            ->humansOnly()
            ->count();
            
        $uniqueVisitorsYesterday = Visitor::whereDate('created_at', Carbon::yesterday())
            ->humansOnly()
            ->distinct('ip_address')
            ->count('ip_address');
            
        // Bot hari ini dan kemarin
        $botsToday = Visitor::whereDate('created_at', Carbon::today())
            ->botsOnly()
            ->count();
            
        $botsYesterday = Visitor::whereDate('created_at', Carbon::yesterday())
            ->botsOnly()
            ->count();
            
        // Hitung persentase perubahan
        $visitorChange = ($visitorsYesterday > 0) 
            ? round((($visitorsToday - $visitorsYesterday) / $visitorsYesterday) * 100) 
            : 0;
        
        $uniqueVisitorChange = ($uniqueVisitorsYesterday > 0)
            ? round((($uniqueVisitorsToday - $uniqueVisitorsYesterday) / $uniqueVisitorsYesterday) * 100)
            : 0;
            
        $botChange = ($botsYesterday > 0)
            ? round((($botsToday - $botsYesterday) / $botsYesterday) * 100)
            : 0;

        // Data 7 hari terakhir untuk chart
        $visitorData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $visitorData[] = Visitor::whereDate('created_at', $date)
                ->humansOnly()
                ->count();
        }

        return [
            Stat::make('Pengunjung Manusia Hari Ini', $visitorsToday)
                ->description($visitorChange >= 0 ? "+ {$visitorChange}% dari kemarin" : "- " . abs($visitorChange) . "% dari kemarin")
                ->descriptionIcon($visitorChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($visitorChange >= 0 ? 'success' : 'danger')
                ->chart($visitorData),

            Stat::make('Pengunjung Unik Hari Ini', $uniqueVisitorsToday)
                ->description($uniqueVisitorChange >= 0 ? "+ {$uniqueVisitorChange}% dari kemarin" : "- " . abs($uniqueVisitorChange) . "% dari kemarin")
                ->descriptionIcon($uniqueVisitorChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($uniqueVisitorChange >= 0 ? 'success' : 'danger'),

            Stat::make('Bot/Crawler Hari Ini', $botsToday)
                ->description($botChange >= 0 ? "+ {$botChange}% dari kemarin" : "- " . abs($botChange) . "% dari kemarin")
                ->descriptionIcon($botChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color('warning'),
        ];
    }
}
