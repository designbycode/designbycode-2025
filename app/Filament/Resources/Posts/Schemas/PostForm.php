<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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

                    Builder::make('content')
                        ->reorderable(true)
                        ->reorderableWithButtons()
                        ->default([
                            ['type' => 'markdown'],
                        ])
                        ->blocks([
                            Builder\Block::make('rich-editor')
                                ->schema([
                                    RichEditor::make('content'),
                                ]),
                            Builder\Block::make('markdown')
                                ->schema([
                                    MarkdownEditor::make('content'),
                                ]),
                            Builder\Block::make('prism')
                                ->schema([
                                    CodeEditor::make('content')
                                        ->reactive()
                                        ->language(Language::JavaScript)
                                        ->afterStateUpdated(function (Set $set, Get $get) {
                                            // Optional: You can dynamically set the language based on the select field
                                            $language = $get('language');
                                            // Note: This might not work directly with CodeEditor in Builder context
                                        }),
                                    Select::make('language')
                                        ->options([
                                            Language::JavaScript->value => 'JavaScript',
                                            Language::Php->value => 'PHP',
                                            Language::Html->value => 'HTML',
                                            Language::Css->value => 'CSS',
                                        ])
                                        ->default(Language::Php->value)
                                        ->required(),
                                ]),
                            Builder\Block::make('image')
                                ->schema([
                                    FileUpload::make('url')
                                        ->disk('public')
                                        ->label('Image')
                                        ->image()
                                        ->directory('post')
                                        ->visibility('public')
                                        ->required(),
                                    TextInput::make('alt')
                                        ->label('Alt text')
                                        ->required(),
                                ]),
                        ]),

                    Toggle::make('live')
                        ->required(),
                    DateTimePicker::make('published_at'),

                ])->columnSpanFull(),
            ]);
    }
}
