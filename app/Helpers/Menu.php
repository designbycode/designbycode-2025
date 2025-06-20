<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class Menu
{
    /**
     * Create a new class instance.
     */
    public static function main( ): Collection
    {
        $items = [
            [
                'name' => 'Home',
                'route' => 'home',
                'active' => 'home',
            ],
            [
                'name' => 'Articles',
                'route' => 'posts.index',
                'active' => 'posts.*',
            ],
            [
                'name' => 'About Us',
                'route' => 'about-us',
                'active' => 'home',
            ],
        ];
        return static::colletor($items);
    }

    /**
     * @param $items
     * @return Collection
     */
    protected static function colletor($items): Collection
    {
        return collect($items)->map(function ($item) {
            return (object)$item;
        });
    }
}
