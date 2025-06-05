<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\News;
use App\Models\Faculty;
use App\Models\Departement;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Fakultas', Faculty::count())
                ->description('Jumlah fakultas aktif')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Total Program Studi', Departement::count())
                ->description('Jumlah program studi yang tersedia')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),

            Stat::make('Total Berita', News::where('is_published', true)->count())
                ->description('Berita yang dipublikasikan')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('warning'),
        ];
    }
}
