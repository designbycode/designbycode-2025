<?php

namespace App\Models;

use App\Traits\HasReadTime;
use Coderflex\Laravisit\Concerns\HasVisits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Package extends Model implements HasMedia
{
    use HasFactory, HasReadTime, HasTags, HasVisits, InteractsWithMedia, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'type',
        'status',
        'website',
        'repository',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    #[SearchUsingPrefix(['id', 'name', 'description'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => (int)$this->id,
            'name' => (string)$this->title,
            'description' => (string)$this->description,
            //            'content' => (string)$this->content,
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Crop, 600, 400)
            ->width(600)
            ->height(400);

        $this
            ->addMediaConversion('main')
            ->fit(Fit::Crop, 1200, 800)
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
            ->addMediaCollection('packages')
            ->useFallbackUrl('https://placehold.co/600x400');

        $this->addMediaCollection('packages_content');
    }
}
