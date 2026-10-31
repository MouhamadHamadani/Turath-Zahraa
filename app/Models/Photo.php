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
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Photo extends Model implements HasMedia
{
    use HasCategories, HasFactory, InteractsWithMedia, Publishable, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => ContentStatus::class,
            'published_at' => 'datetime',
            'taken_at' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile(); // original stays hi-res for the zoom viewer
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(400)->nonQueued();
        $this->addMediaConversion('web')->width(1600);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
