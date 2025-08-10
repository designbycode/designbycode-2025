<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Forms\Components\ContentBuilder;
use App\Models\Post;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('featured_image')
                    ->collection('posts')
                    ->columnSpanFull()
                    ->multiple(false)
                    ->image(),
                Section::make()->schema([
                    TextInput::make('title')
                        ->unique(ignoreRecord: true)
                        ->required(),
                    Textarea::make('description')
                        ->rows(5)
                        ->autosize()
                        ->columnSpanFull(),
                    SpatieTagsInput::make('tags')
                        ->splitKeys(['Tab', ' '])
                        ->type('posts'),
                    ContentBuilder::make(Post::class),
                    Toggle::make('live')
                        ->required(),
                    DateTimePicker::make('published_at'),
                ])->columnSpanFull(),
            ]);
    }
}
