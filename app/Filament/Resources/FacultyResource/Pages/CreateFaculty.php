<?php

namespace App\Filament\Resources\FacultyResource\Pages;

use App\Filament\Resources\FacultyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFaculty extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = FacultyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
