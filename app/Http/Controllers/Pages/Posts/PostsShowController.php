<?php

namespace App\Http\Controllers\Pages\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;

class PostsShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Post $post): View
    {

        defer(function () use ($post) {
            $post->visit();
        });

        return view('pages.posts.show', [
            'post' => $post->load('author'),
        ]);
    }
}
