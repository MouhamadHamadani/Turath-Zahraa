<?php

namespace App\Livewire\Archive;

use App\Enums\CategoryType;
use App\Livewire\ContentListing;
use App\Models\Document;

class Documents extends ContentListing
{
    protected string $model = Document::class;

    protected string $icon = 'fa-solid fa-scroll';

    protected function heading(): string
    {
        return 'الوثائق';
    }

    protected function description(): string
    {
        return 'مخطوطات ووثائق تاريخية وصفحات قديمة ومخاطبات نادرة.';
    }

    protected function categoryType(): CategoryType
    {
        return CategoryType::Document;
    }
}
