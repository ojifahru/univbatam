<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use App\Models\News;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListNews extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }


    public function getTabs(): array
    {
        // Cache the counts to avoid multiple queries
        $allCount = \Cache::remember('news_all_count', 60, function () {
            return News::count();
        });

        $newsCount = \Cache::remember('news_category_2_count', 60, function () {
            return News::where('category_id', 2)->count();
        });

        $announcementsCount = \Cache::remember('news_category_3_count', 60, function () {
            return News::where('category_id', 3)->count();
        });

        return [
            'all' => Tab::make()
                ->label('All News')
                ->icon('heroicon-o-document-text')
                ->badge($allCount),
            'active' => Tab::make()
                ->label('News')
                ->icon('heroicon-o-newspaper')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('category_id', 2)->with(['category', 'user']))
                ->badge($newsCount),
            'inactive' => Tab::make()
                ->label('Announcements')
                ->icon('heroicon-o-bell')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('category_id', 3)->with(['category', 'user']))
                ->badge($announcementsCount),
        ];
    }
}
