<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Publishable, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => ContentStatus::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    /** Not over yet: starts in the future, or is still running. */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query
            ->where(fn (Builder $q) => $q->where('starts_at', '>=', now())->orWhere('ends_at', '>=', now()))
            ->orderBy('starts_at');
    }

    public function scopePast(Builder $query): Builder
    {
        return $query
            ->where('starts_at', '<', now())
            ->where(fn (Builder $q) => $q->whereNull('ends_at')->orWhere('ends_at', '<', now()))
            ->orderByDesc('starts_at');
    }

    public function getIsUpcomingAttribute(): bool
    {
        return ($this->ends_at ?? $this->starts_at)->isFuture();
    }

    /** 'upcoming', 'ongoing' or 'past', derived from the dates. */
    public function getTimelineStatusAttribute(): string
    {
        if ($this->starts_at->isFuture()) {
            return 'upcoming';
        }

        return $this->ends_at?->isFuture() ? 'ongoing' : 'past';
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function audioTracks(): HasMany
    {
        return $this->hasMany(AudioTrack::class);
    }

    public function news(): HasMany
    {
        return $this->hasMany(NewsArticle::class);
    }
}
