<?php

use App\Models\Post;
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
    $response = $this->get('/tutorials');
    $response->assertStatus(200);
});

test('posts show page returns a successful response for existing post', function () {
    // Create a dummy post
    $post = Post::factory()->create(['slug' => 'test-post-slug']);

    $response = $this->get('/tutorials/' . $post->slug);
    $response->assertStatus(200);
});

test('posts show page returns a 404 for non-existing post', function () {
    $response = $this->get('/tutorials/non-existing-slug');
    $response->assertStatus(404);
});

test('test markdown table page returns a successful response in testing environment', function () {
    // Ensure APP_ENV is 'testing' for this route to be active as per routes/web.php
    // Pest/PHPUnit automatically sets APP_ENV to 'testing'
    $response = $this->get('/test-markdown-table');
    $response->assertStatus(200);
});
