<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Person extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $table = 'people';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_listed' => 'boolean', 'is_featured' => 'boolean'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'author_id');
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class, 'author_id');
    }

    public function researchPapers(): HasMany
    {
        return $this->hasMany(ResearchPaper::class, 'researcher_id');
    }

    public function audioTracks(): HasMany
    {
        return $this->hasMany(AudioTrack::class, 'speaker_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'speaker_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }
}
