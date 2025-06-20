<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Name')->schema([
                    TextInput::make('name')
                        ->required(),
                    Textarea::make('description')
                        ->columnSpanFull(),
                    TextInput::make('type'),
                ])->columnSpanFull()
            ]);
    }
}
