<?php

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

// test('posts show page returns a successful response for existing post', function () {
//    $post = Post::factory()->create([
//        'slug' => 'dummy-post-slug',
//        'title' => 'Test Post',
//        'body' => 'Some content here'
//    ]);
//    $response = $this->get('/tutorials/' . $post->slug);
//
//    $response->assertStatus(200);
// });

test('posts show page returns a 404 for non-existing post', function () {
    $response = $this->get('/tutorials/non-existing-slug');
    $response->assertStatus(404);
});
