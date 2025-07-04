<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function categorizables(): MorphToMany
    {
        return $this->morphedByMany(Model::class, 'categorizable');
    }

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'categorizable');
    }
}
