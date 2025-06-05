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
        return [
            'all' => Tab::make()
                ->label('All News')
                ->icon('heroicon-o-document-text')
                ->badge(News::count()),
            'active' => Tab::make()
                ->label('News')
                ->icon('heroicon-o-newspaper')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('category_id', 2))
                ->badge(News::where('category_id', 2)->count()),
            'inactive' => Tab::make()
                ->label('Announcements')
                ->icon('heroicon-o-bell')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('category_id', 3))
                ->badge(News::where('category_id', 3)->count()),
        ];
    }
}
