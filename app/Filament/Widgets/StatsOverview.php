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
        // Cache the counts to avoid multiple queries
        $facultyCount = \Cache::remember('faculty_count', 60, function () {
            return Faculty::count();
        });

        $departementCount = \Cache::remember('departement_count', 60, function () {
            return Departement::count();
        });

        $newsCount = \Cache::remember('published_news_count', 60, function () {
            return News::where('is_published', true)->count();
        });

        return [
            Stat::make('Total Fakultas', $facultyCount)
                ->description('Jumlah fakultas aktif')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Total Program Studi', $departementCount)
                ->description('Jumlah program studi yang tersedia')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),

            Stat::make('Total Berita', $newsCount)
                ->description('Berita yang dipublikasikan')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('warning'),
        ];
    }
}
