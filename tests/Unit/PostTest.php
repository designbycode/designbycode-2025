<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

// it('can be created using factory', function () {
//    $post = Post::factory()->create();
//    expect($post)->toBeInstanceOf(Post::class)
//        ->and($post->exists)->toBeTrue();
// });

it('has an author', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    expect($post->author)->toBeInstanceOf(User::class);
});

it('returns an empty array when content is null', function () {
    $post = Post::factory()->create(['content' => null]);
    expect($post->getContentBlocksAttribute())->toBe([]);
});

it('returns content blocks as an array', function () {
    $content = [
        ['type' => 'markdown', 'data' => ['content' => 'Hello World']],
    ];

    $post = Post::factory()->create(['content' => json_encode($content)]);

    expect($post->getContentBlocksAttribute())->toBe($content);
});

it('can be created with all fillable attributes', function () {
    $user = User::factory()->create();

    $postData = [
        'title' => 'Test Post Title',
        'slug' => 'test-post-title',
        'description' => 'This is a test post description',
        'content' => [
            [
                'type' => 'rich-editor',
                'data' => ['content' => '<p>This is rich text content</p>'],
            ],
        ],
        'live' => true,
        'user_id' => $user->id,
        'published_at' => now(),
    ];

    $post = Post::create($postData);

    expect($post->title)->toBe($postData['title'])
        ->and($post->slug)->toBe($postData['slug'])
        ->and($post->description)->toBe($postData['description'])
        ->and($post->content)->toBe($postData['content'])
        ->and($post->live)->toBe($postData['live'])
        ->and($post->user_id)->toBe($postData['user_id']);
});

it('casts content to array', function () {
    $contentArray = [
        ['type' => 'rich-editor', 'data' => ['content' => 'Test content']],
    ];

    $post = Post::factory()->create(['content' => $contentArray]);

    expect($post->content)->toBeArray()
        ->and($post->content)->toBe($contentArray);
});

it('returns null for estimated read time when content is empty', function () {
    $post = Post::factory()->create(['content' => '[]']);
    expect($post->estimatedReadTime)->toBeNull();
});

it('casts live to boolean', function () {
    $post = Post::factory()->create(['live' => 1]);
    expect($post->live)->toBeTrue();

    $post2 = Post::factory()->create(['live' => 0]);
    expect($post2->live)->toBeFalse();
});

it('casts published_at to datetime', function () {
    $publishedAt = '2024-01-15 10:30:00';
    $post = Post::factory()->create(['published_at' => $publishedAt]);

    expect($post->published_at)->toBeInstanceOf(Carbon::class);
});

it('belongs to an author user', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    expect($post->author)->toBeInstanceOf(User::class)
        ->and($post->author->id)->toBe($user->id);
});

it('filters live posts with scopeLive', function () {
    // Create live posts (published)
    Post::factory()->count(2)->create([
        'live' => true,
        'published_at' => now()->subDay(),
    ]);

    // Create draft posts
    Post::factory()->count(2)->create([
        'live' => false,
        'published_at' => now()->subDay(),
    ]);

    // Create future posts
    Post::factory()->count(2)->create([
        'live' => true,
        'published_at' => now()->addDay(),
    ]);

    $livePosts = Post::live()->get();

    expect($livePosts)->toHaveCount(2);

    $livePosts->each(function ($post) {
        expect($post->live)->toBeTrue()
            ->and($post->published_at)->toBeLessThanOrEqual(now());
    });
});

// it('returns content blocks attribute', function () {
//    $contentArray = [
//        ['type' => 'rich-editor', 'data' => ['content' => 'Test content']]
//    ];
//
//    $post = Post::factory()->create(['content' => $contentArray]);
//
//    expect($post->getContentBlocksAttribute())->toBe($contentArray);
// });
//
// it('returns empty array for null content in content blocks', function () {
//    $post = Post::factory()->create(['content' => null]);
//
//    expect($post->getContentBlocksAttribute())->toBe([]);
// });

it('calculates estimated read time for rich-editor content', function () {
    $content = [
        [
            'type' => 'rich-editor',
            'data' => ['content' => '<p>'.str_repeat('word ', 200).'</p>'], // 200 words
        ],
    ];

    $post = Post::factory()->create(['content' => $content]);

    expect($post->estimatedReadTime)->toBe(1.0); // 200 words / 200 wpm = 1 minute
});

it('handles blocks without proper structure in estimated read time', function () {
    $content = [
        ['invalid' => 'block'],
        [
            'type' => 'rich-editor',
            'data' => ['content' => str_repeat('word ', 100)],
        ],
    ];

    $post = Post::factory()->create(['content' => $content]);

    expect($post->estimatedReadTime)->toBe(1.0); // Only counts valid blocks
});

it('can be soft deleted', function () {
    $post = Post::factory()->create();

    $post->delete();

    expect($post->trashed())->toBeTrue()
        ->and(Post::count())->toBe(0)
        ->and(Post::withTrashed()->count())->toBe(1);
});

it('can be restored after soft delete', function () {
    $post = Post::factory()->create();

    $post->delete();
    $post->restore();

    expect($post->trashed())->toBeFalse()
        ->and(Post::count())->toBe(1);
});

it('returns correct searchable array', function () {
    $post = Post::factory()->create([
        'title' => 'Test Title',
        'description' => 'Test Description',
        'content' => [
            ['type' => 'rich-editor', 'data' => ['content' => 'Test content']],
        ],
    ]);

    $searchableArray = $post->toSearchableArray();

    expect($searchableArray)->toHaveKey('id')
        ->and($searchableArray)->toHaveKey('title')
        ->and($searchableArray)->toHaveKey('description')
        ->and($searchableArray['id'])->toBeInt()
        ->and($searchableArray['title'])->toBeString()
        ->and($searchableArray['description'])->toBeString();
});

it('uses HasFactory trait', function () {
    expect(method_exists(Post::class, 'factory'))->toBeTrue();
});

it('uses SoftDeletes trait', function () {
    $post = new Post;

    expect(method_exists($post, 'delete'))->toBeTrue()
        ->and(method_exists($post, 'restore'))->toBeTrue()
        ->and(method_exists($post, 'trashed'))->toBeTrue();
});

it('uses Searchable trait', function () {
    $post = new Post;

    expect(method_exists($post, 'toSearchableArray'))->toBeTrue();
});

// Edge Case Tests
it('handles null values gracefully', function () {
    $post = Post::factory()->create([
        'content' => null,
        'published_at' => null,
    ]);

    expect($post->content)->toBeNull()
        ->and($post->published_at)->toBeNull()
        ->and($post->getContentBlocksAttribute())->toBe([])
        ->and($post->estimatedReadTime)->toBeNull();
});

it('handles empty string content', function () {
    $post = Post::factory()->create(['content' => '']);

    expect($post->content)->toBe('')
        ->and($post->getContentBlocksAttribute())->toBe([]);
});
