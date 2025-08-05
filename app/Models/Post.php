<?php

namespace App\Models;

use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Post extends Model implements CanVisit
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasVisits, Searchable, SoftDeletes;

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

    // Scope to filter posts that are live and already published
    public function scopeLive(Builder $query): Builder
    {
        return $query->where('live', true)
            ->where('published_at', '<=', now());
    }

    /**
     * @return array|mixed
     */
    public function getContentBlocksAttribute(): mixed
    {
        return json_decode($this->content, true) ?? [];
    }

    public function estimatedReadTime(): Attribute
    {
        return Attribute::get(function () {
            if (empty($this->content) || ! is_array($this->content)) {
                return null;
            }

            $combinedText = '';

            foreach ($this->content as $block) {
                if (! isset($block['type'], $block['data']['content'])) {
                    continue;
                }

                $content = $block['data']['content'];

                switch ($block['type']) {
                    case 'markdown':
                    case 'prism':
                        // Keep Markdown and code content as-is
                        $combinedText .= ' '.$content;
                        break;
                    case 'rich-editor':
                        // Strip HTML tags for rich text content
                        $combinedText .= ' '.strip_tags($content);
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

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    #[SearchUsingPrefix(['id', 'title', 'description'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'title' => (string) $this->title,
            'description' => (string) $this->description,
            //            'content' => (string)$this->content,
        ];
    }
}
