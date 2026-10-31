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

class AudioTrack extends Model implements HasMedia
{
    use HasCategories, HasFactory, InteractsWithMedia, Publishable, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => ContentStatus::class,
            'published_at' => 'datetime',
            'recorded_at' => 'date',
            'is_downloadable' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('audio')->useDisk(config('filesystems.audio_disk'))->singleFile();
        $this->addMediaCollection('thumbnail')->singleFile();
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
