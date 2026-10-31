<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Models\Concerns\HasCategories;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Video extends Model implements HasMedia
{
    use HasCategories, HasFactory, InteractsWithMedia, Publishable, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => ContentStatus::class,
            'published_at' => 'datetime',
            'recorded_at' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')->singleFile(); // optional; falls back to YouTube thumbnail
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('thumbnail') ?: "https://i.ytimg.com/vi/{$this->youtube_id}/hqdefault.jpg";
    }

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'speaker_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
