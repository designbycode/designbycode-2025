<?php

namespace App\Models;

use App\Traits\HasReadTime;
use Coderflex\Laravisit\Concerns\HasVisits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Package extends Model implements HasMedia
{
    use HasFactory, HasReadTime, HasTags, HasVisits, InteractsWithMedia, SoftDeletes;

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

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('posts')
            ->useFallbackUrl('https://placehold.co/600x400')
            ->useFallbackUrl('https://placehold.co/600x400', 'preview')
            ->useFallbackUrl('https://placehold.co/1200x800', 'main')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('preview')
                    ->width(600)
                    ->height(400);

                $this
                    ->addMediaConversion('main')
                    ->width(1200)
                    ->height(800);
            });
    }
}
