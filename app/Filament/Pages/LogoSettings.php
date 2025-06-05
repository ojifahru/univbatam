<?php

namespace App\Filament\Pages;

use App\Models\Logo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Storage;

class LogoSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.logo-settings';
    protected static ?string $navigationLabel = 'Logo Settings';
    protected static ?string $navigationGroup = 'Website Settings';
    protected static ?int $navigationSort = 10;


    // Define the form data property
    public ?array $data = [];

    // Set up the form when the page loads
    public function mount(): void
    {
        $logo = Logo::first() ?? new Logo();

        $this->form->fill([
            'logo' => $logo->logo,
            'favicon' => $logo->favicon,
            'footer_logo' => $logo->footer_logo,
        ]);
    }

    // Define the form
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->disk('public')
                    ->directory('logos')
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->maxSize(2048)
                    ->columnSpanFull(),

                Components\FileUpload::make('favicon')
                    ->label('Favicon')
                    ->image()
                    ->disk('public')
                    ->directory('favicons')
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(2048)
                    ->columnSpanFull(),

                Components\FileUpload::make('footer_logo')
                    ->label('Footer Logo')
                    ->image()
                    ->disk('public')
                    ->directory('footer-logos')
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(2048)
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    // Handle form submission
    public function submit(): void
    {
        $data = $this->form->getState();

        $logo = Logo::first() ?? new Logo();

        $logo->fill([
            'logo' => $data['logo'],
            'favicon' => $data['favicon'],
            'footer_logo' => $data['footer_logo'],
        ])->save();

        Notification::make()
            ->title('Logos updated successfully!')
            ->success()
            ->send();
    }
}
