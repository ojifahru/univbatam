<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VisitorDetailsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Visitor::query()->latest()->limit(50)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('page_visited')
                    ->label('Halaman')
                    ->formatStateUsing(function (string $state): string {
                        $url = parse_url($state);
                        $path = $url['path'] ?? '/';
                        if ($path === '/') $path = 'Homepage';
                        return Str::limit($path, 30);
                    })
                    ->tooltip(fn (Visitor $record): string => $record->page_visited)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('device_type')
                    ->label('Perangkat')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => 
                        match($state) {
                            'mobile' => 'success',
                            'tablet' => 'warning',
                            'bot' => 'danger',
                            default => 'info',
                        }
                    ),
                    
                Tables\Columns\IconColumn::make('is_bot')
                    ->label('Bot')
                    ->boolean()
                    ->trueColor('danger')
                    ->tooltip(fn (Visitor $record): ?string => $record->bot_name),
                    
                Tables\Columns\TextColumn::make('bot_name')
                    ->label('Jenis Bot')
                    ->placeholder('-'),
                    
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('referer_url')
                    ->label('Referer')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        $referer = parse_url($state);
                        return $referer['host'] ?? Str::limit($state, 30);
                    })
                    ->tooltip(fn (Visitor $record): ?string => $record->referer_url)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('country')
                    ->label('Negara')
                    ->placeholder('Unknown'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('device_type')
                    ->options([
                        'desktop' => 'Desktop',
                        'mobile' => 'Mobile',
                        'tablet' => 'Tablet',
                        'bot' => 'Bot',
                    ]),
                
                Tables\Filters\SelectFilter::make('is_bot')
                    ->label('Tipe Visitor')
                    ->options([
                        '0' => 'Manusia',
                        '1' => 'Bot/Crawler',
                    ]),
                    
                Tables\Filters\Filter::make('today')
                    ->label('Hari Ini')
                    ->query(fn ($query) => $query->whereDate('created_at', Carbon::today())),
                    
                Tables\Filters\Filter::make('yesterday')
                    ->label('Kemarin')
                    ->query(fn ($query) => $query->whereDate('created_at', Carbon::yesterday())),
            ])
            ->defaultSort('created_at', 'desc')
            ->heading('Pengunjung Terbaru');
    }
}
