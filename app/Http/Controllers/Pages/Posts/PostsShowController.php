<?php

namespace App\Http\Controllers\Pages\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostsShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Post $post)
    {
       
        return view('pages.posts.show', [
            'post' => $post
        ]);
    }
}
