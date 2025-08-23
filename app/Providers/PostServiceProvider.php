<?php

namespace App\Providers;

use App\Models\Post;
use App\Observers\PostObserver;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
 
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Post::observe(PostObserver::class);
    }
}
