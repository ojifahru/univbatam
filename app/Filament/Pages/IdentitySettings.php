<?php

namespace App\Filament\Pages;

use App\Models\Identity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class IdentitySettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static string $view = 'filament.pages.identity-settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'University Identity';
    protected static ?int $navigationSort = 1;

    // Define the form data property
    public ?array $data = [];

    // Set up the form when the page loads
    public function mount(): void
    {
        $identity = Identity::first() ?? new Identity();

        $this->form->fill([
            'name' => $identity->name,
            'email' => $identity->email,
            'domain' => $identity->domain,
            'address' => $identity->address,
            'phone' => $identity->phone,
            'meta_description' => $identity->meta_description,
            'meta_keywords' => $identity->meta_keywords,
            'maps' => $identity->maps,
            'facebook' => $identity->facebook,
            'twitter' => $identity->twitter,
            'instagram' => $identity->instagram,
            'linkedin' => $identity->linkedin,
            'whatsapp' => $identity->whatsapp,
        ]);
    }

    // Define the form
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Section::make('Basic Information')
                    ->schema([
                        Components\TextInput::make('name')
                            ->label('University Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Components\TextInput::make('domain')
                            ->label('Website Domain')
                            ->required()
                            ->url()
                            ->prefix('https://')
                            ->maxLength(255),

                        Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ]),

                Components\Section::make('Address Information')
                    ->schema([
                        Components\Textarea::make('address')
                            ->label('Address')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Components\Textarea::make('maps')
                            ->label('Google Maps Embed Code')
                            ->helperText('Paste the iframe embed code from Google Maps here')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Components\Section::make('SEO Information')
                    ->schema([
                        Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->helperText('A short description of your university for search engines (max 160 characters)')
                            ->maxLength(160)
                            ->rows(2)
                            ->columnSpanFull(),

                        Components\TextInput::make('meta_keywords')
                            ->label('Meta Keywords')
                            ->helperText('Comma separated keywords related to your university')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Components\Section::make('Social Media Links')
                    ->schema([
                        Components\TextInput::make('facebook')
                            ->label('Facebook')
                            ->url()
                            ->prefix('https://'),

                        Components\TextInput::make('twitter')
                            ->label('Twitter/X')
                            ->url()
                            ->prefix('https://'),

                        Components\TextInput::make('instagram')
                            ->label('Instagram')
                            ->url()
                            ->prefix('https://'),

                        Components\TextInput::make('linkedin')
                            ->label('LinkedIn')
                            ->url()
                            ->prefix('https://'),

                        Components\TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->helperText('Include country code (e.g. 628123456789)')
                            ->tel()
                            ->prefix('+'),
                    ]),
            ])
            ->statePath('data');
    }

    // Handle form submission
    public function submit(): void
    {
        $data = $this->form->getState();

        $identity = Identity::first() ?? new Identity();

        $identity->fill($data)->save();

        Notification::make()
            ->title('University identity updated successfully!')
            ->success()
            ->send();
    }
}
