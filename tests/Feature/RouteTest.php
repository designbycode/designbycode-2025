<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home page returns a successful response', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

test('about us page returns a successful response', function () {
    $response = $this->get('/about-us');
    $response->assertStatus(200);
});

test('posts index page returns a successful response', function () {
    $response = $this->get('/articles');
    $response->assertStatus(200);
});

test('posts show page returns a successful response for existing post', function () {
    $post = Post::factory()->create([
        'title' => 'Test Post',
        'slug' => 'dummy-post-slug',
        'content' => [
            [
                'type' => 'markdown',
                'data' => [
                    'content' => "\n\n\n### Tags  \nnpm, pnpm, Yarn, Bun, JavaScript, package managers, Node.js, web development",
                ],
            ],
        ],
        'user_id' => User::factory()->create()
    ]);
    $response = $this->get(route('posts.show', $post->slug));

    $response->assertStatus(200);
});

test('posts show page returns a 404 for non-existing post', function () {
    $response = $this->get('/tutorials/non-existing-slug');
    $response->assertStatus(404);
});
