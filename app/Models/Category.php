<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['type' => CategoryType::class, 'is_active' => 'boolean'];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeOfType(Builder $query, CategoryType $type): Builder
    {
        return $query->where('type', $type->value)->where('is_active', true)->orderBy('sort_order');
    }

    // One inverse per taggable model (needed by the heritage page + unified search).
    public function books(): MorphToMany
    {
        return $this->morphedByMany(Book::class, 'categorizable');
    }

    public function publications(): MorphToMany
    {
        return $this->morphedByMany(Publication::class, 'categorizable');
    }

    public function researchPapers(): MorphToMany
    {
        return $this->morphedByMany(ResearchPaper::class, 'categorizable');
    }

    public function audioTracks(): MorphToMany
    {
        return $this->morphedByMany(AudioTrack::class, 'categorizable');
    }

    public function videos(): MorphToMany
    {
        return $this->morphedByMany(Video::class, 'categorizable');
    }

    public function photos(): MorphToMany
    {
        return $this->morphedByMany(Photo::class, 'categorizable');
    }

    public function documents(): MorphToMany
    {
        return $this->morphedByMany(Document::class, 'categorizable');
    }

    public function newsArticles(): MorphToMany
    {
        return $this->morphedByMany(NewsArticle::class, 'categorizable');
    }

    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Article::class, 'categorizable');
    }
}
