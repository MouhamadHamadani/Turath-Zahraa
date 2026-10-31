<?php

namespace App\Livewire\Archive;

use App\Enums\CategoryType;
use App\Livewire\ContentListing;
use App\Models\Photo;

class Photos extends ContentListing
{
    protected string $model = Photo::class;

    protected string $icon = 'fa-regular fa-images';

    protected function heading(): string
    {
        return 'الصور';
    }

    protected function description(): string
    {
        return 'صور الفعاليات والشخصيات والإصدارات والصور التاريخية.';
    }

    protected function categoryType(): CategoryType
    {
        return CategoryType::Photo;
    }

    protected function searchColumns(): array
    {
        return ['title', 'caption'];
    }
}
