<?php

namespace App\Models;

use App\Traits\HasReadTime;
use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Post extends Model implements CanVisit, HasMedia
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasReadTime, HasTags, HasVisits, InteractsWithMedia, Searchable, SoftDeletes;

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

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    #[SearchUsingPrefix(['id', 'title', 'description'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => (int)$this->id,
            'title' => (string)$this->title,
            'description' => (string)$this->description,
            //            'content' => (string)$this->content,
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->width(600)
            ->height(400);

        $this
            ->addMediaConversion('main')
            ->width(1200)
            ->height(800);
    }

    /**
     * Define the static media collections. Note that the dynamic
     * collections from the Builder block don't need to be defined here.
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('posts')
            ->useFallbackUrl('https://placehold.co/600x400');
    }
}
