<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class ContentBuilder
{
    public static function make(string $modelClass): Builder
    {
        $model = new $modelClass();
        $baseCollectionName = $model->getTable();
        $contentCollectionName = $baseCollectionName . '_content';
        $directory = Str::singular($baseCollectionName);

        return Builder::make('content')
            ->reorderable(true)
            ->reorderableWithButtons()
            ->default([
                ['type' => 'markdown'],
            ])
            ->blocks([
                Builder\Block::make('rich-editor')
                    ->schema([
                        RichEditor::make('content')
                            ->required(),
                    ]),
                Builder\Block::make('markdown')
                    ->schema([
                        MarkdownEditor::make('content')
                            ->required(),
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
                            })
                            ->required(),
                        Select::make('language')
                            ->options([
                                Language::JavaScript->value => 'JavaScript',
                                Language::Php->value => 'PHP',
                                Language::Html->value => 'HTML',
                                Language::Css->value => 'CSS',
                                Language::Json->value => 'JSON',
                                Language::Yaml->value => 'YAML',
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
                            ->directory($directory)
                            ->visibility('public')
                            ->required(),
                        TextInput::make('alt')
                            ->label('Alt text')
                            ->required(),
                    ]),
                Builder\Block::make('spatie-image')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('attachment')
                            ->collection(function (Get $get) {
                                // Get the collection name from the block data
                                $collectionName = $get('collection_name');

                                // If no collection name exists, generate a new one
                                if (empty($collectionName)) {
                                    return 'block-' . Str::uuid();
                                }

                                return $collectionName;
                            })
                            ->multiple()
                            ->reorderable()
                            ->panelLayout('grid')
                            ->required(),
                        TextInput::make('alt')
                            ->label('Alt text')
                            ->required(),
                        Hidden::make('collection_name')
                            ->default(function (Get $get) {
                                // Only generate new UUID if collection_name doesn't exist
                                $existingCollection = $get('collection_name');
                                return $existingCollection ?: 'block-' . Str::uuid();
                            })
                            ->dehydrated()
                            ->afterStateHydrated(function (Set $set, Get $get, $state) {
                                // Ensure we have a collection name when loading existing data
                                if (empty($state)) {
                                    $set('collection_name', 'block-' . Str::uuid());
                                }
                            }),
                    ])
            ]);
    }
}
