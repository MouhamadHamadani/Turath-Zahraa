<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\PublicationType;
use App\Models\Concerns\HasCategories;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Publication extends Model implements HasMedia
{
    use HasCategories, HasFactory, InteractsWithMedia, Publishable, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'type' => PublicationType::class,
            'status' => ContentStatus::class,
            'published_at' => 'datetime',
            'is_downloadable' => 'boolean',
            'allow_online_reading' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('pdf')->singleFile();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'author_id');
    }
}
