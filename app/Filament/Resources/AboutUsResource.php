<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutUsResource\Pages;
use App\Models\AboutUs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Concerns\Translatable;

class AboutUsResource extends Resource
{
    use Translatable;

    protected static ?string $model = AboutUs::class;
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'Profile Information';
    protected static ?string $navigationGroup = 'University Management';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->label('Key')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->helperText('Slug will be automatically generated from this key'),
                // Optionally show the slug field as disabled for reference
                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated(false) // Important - don't include in form submission
                    ->label('Slug')
                    ->helperText('Automatically generated from key')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('value')
                    ->required()
                    ->label('Value')
                    ->columnSpanFull()
                    ->fileAttachmentsDirectory('about-us')
                    ->fileAttachmentsDisk('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('value')
                    ->html()
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAboutUs::route('/'),
            'create' => Pages\CreateAboutUs::route('/create'),
            'edit' => Pages\EditAboutUs::route('/{record}/edit'),
        ];
    }
}
