<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
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
                Section::make()->schema([
                    TextInput::make('title')
                        ->required(),
                    Textarea::make('description')
                        ->columnSpanFull(),

                    MarkdownEditor::make('content')
                        ->columnSpanFull(),

                    Toggle::make('live')
                        ->required(),
                    DateTimePicker::make('published_at'),

                ])->columnSpanFull(),
            ]);
    }
}
