<?php

namespace App\Models;

use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class Post extends Model implements CanVisit
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, SoftDeletes, HasVisits;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'live',
        'user_id',
        'published_at',
    ];

    protected $casts = [
        'content' => 'array',
        'live' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function getContentBlocksAttribute()
    {
        return json_decode($this->content, true) ?? [];
    }

    public function estimatedReadTime(): Attribute
    {
        return Attribute::get(function () {
            if (empty($this->content) || !is_array($this->content)) {
                return null;
            }

            $combinedText = '';

            foreach ($this->content as $block) {
                if (!isset($block['type'], $block['data']['content'])) {
                    continue;
                }

                $content = $block['data']['content'];

                switch ($block['type']) {
                    case 'markdown':
                    case 'prism':
                        // Keep markdown and code content as-is
                        $combinedText .= ' ' . $content;
                        break;
                    case 'rich-editor':
                        // Strip HTML tags for rich text content
                        $combinedText .= ' ' . strip_tags($content);
                        break;
                    default:
                        break;
                }
            }

            $wpm = 200;
            $wordCount = str_word_count($combinedText);

            return ceil($wordCount / $wpm);
        });
    }

    public function logView(): void
    {
        Redis::pfadd(sprintf('posts.%s.views', $this->id), [request()->ip()]);
    }

    public function getViewCount()
    {
        return Redis::pfcount(sprintf('posts.%s.views', $this->id));
    }
}
