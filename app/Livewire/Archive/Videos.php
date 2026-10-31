<?php

namespace App\Livewire\Archive;

use App\Enums\CategoryType;
use App\Livewire\ContentListing;
use App\Models\Video;

class Videos extends ContentListing
{
    protected string $model = Video::class;

    protected string $icon = 'fa-solid fa-video';

    protected function heading(): string
    {
        return 'الأرشيف المرئي';
    }

    protected function description(): string
    {
        return 'محاضرات وبرامج وندوات وأفلام وثائقية مختارة من قناة المؤسسة.';
    }

    protected function categoryType(): CategoryType
    {
        return CategoryType::Video;
    }

    protected function relations(): array
    {
        return ['speaker'];
    }
}
