<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class Menu
{
    /**
     * Create a new class instance.
     */
    public static function main(): Collection
    {
        $items = [
            [
                'name' => 'Home',
                'route' => 'home',
                'active' => 'home',
            ],
            [
                'name' => 'Tutorials',
                'route' => 'posts.index',
                'active' => 'posts.*',
            ],
//            [
//                'name' => 'Tools',
//                'route' => 'tools.favicon-converter',
//                'active' => 'tools.*',
//            ],
        ];

        return static::colletor($items);
    }

    protected static function colletor($items): Collection
    {
        return collect($items)->map(function ($item) {
            return (object)$item;
        });
    }
}
