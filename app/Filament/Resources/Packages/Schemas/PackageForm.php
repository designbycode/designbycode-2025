<?php

namespace App\Filament\Resources\Packages\Schemas;

use App\Filament\Forms\Components\ContentBuilder;
use App\Models\Package;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('featured_image')
                    ->collection('packages')
                    ->multiple(false)
                    ->columnSpanFull()
                    ->image(),

                Section::make()->schema([
                    TextInput::make('name')
                        ->unique(ignoreRecord: true)
                        ->required(),

                    Textarea::make('description')
                        ->columnSpanFull(),

                    SpatieTagsInput::make('tags')
                        ->splitKeys(['Tab', ' '])
                        ->type('packages'),

                    Select::make('type')
                        ->options([
                            'alpine' => 'Alpine',
                            'tailwindcss' => 'Tailwind CSS',
                            'typescript' => 'Typescript',
                        ])
                        ->required(),

                    ContentBuilder::make(Package::class),

                ])->columnSpanFull(),
            ]);
    }
}
