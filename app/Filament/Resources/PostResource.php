<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Post Title'),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->label('Post Content'),

                \Filament\Forms\Components\Select::make('category')
                    ->label('Category')
                    ->options(self::resolveDropdownOptions())
                    ->searchable()
                    ->placeholder('Select a category')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Content')
                    ->limit(50), // Show only first 50 characters in the table
                    
            ])
            ->filters([
                // Add table filters if needed
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('customAction')
                    ->label('Custom Action')
                    ->action(function ($record) {
                        Notification::make()
                            ->title('Custom Action Triggered')
                            ->body('You triggered a custom action on the post: ' . $record->title)
                            ->success()
                            ->send();
                    })
                    ->icon('heroicon-o-bell')
                    ->color('primary'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add any relation managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    
    private static function resolveDropdownOptions()
    {
        return [
            'technology' => 'Technology',
            'health' => 'Health',
            'finance' => 'Finance',
            'education' => 'Education',
            'entertainment' => 'Entertainment',
        ];
    }
}
