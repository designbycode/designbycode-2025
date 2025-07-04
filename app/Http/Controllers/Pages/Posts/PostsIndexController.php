<?php

namespace App\Http\Controllers\Pages\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PostsIndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Post $posts): View
    {
        $posts = Cache::flexible('posts', [10, 360], function () use ($posts) {
            return $posts->with('author')->withTotalVisitCount()->latest()->paginate(10);
        });

        return view('pages.posts.index', [
            'posts' => $posts,
        ]);
    }
}
