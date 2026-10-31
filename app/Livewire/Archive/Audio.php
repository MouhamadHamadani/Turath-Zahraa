<?php

namespace App\Livewire\Archive;

use App\Enums\CategoryType;
use App\Livewire\ContentListing;
use App\Models\AudioTrack;

class Audio extends ContentListing
{
    protected string $model = AudioTrack::class;

    protected string $icon = 'fa-solid fa-headphones';

    protected function heading(): string
    {
        return 'الأرشيف الصوتي';
    }

    protected function description(): string
    {
        return 'محاضرات وخطب ومجالس وندوات وتسجيلات تاريخية مؤرشفة.';
    }

    protected function categoryType(): CategoryType
    {
        return CategoryType::Audio;
    }

    protected function relations(): array
    {
        return ['speaker'];
    }
}
