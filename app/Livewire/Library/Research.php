<?php

namespace App\Livewire\Library;

use App\Enums\CategoryType;
use App\Livewire\ContentListing;
use App\Models\ResearchPaper;

class Research extends ContentListing
{
    protected string $model = ResearchPaper::class;

    protected string $icon = 'fa-solid fa-graduation-cap';

    protected function heading(): string
    {
        return 'البحوث';
    }

    protected function description(): string
    {
        return 'بحوث ودراسات علمية محكّمة في تراث الصديقة الزهراء عليها السلام.';
    }

    protected function categoryType(): CategoryType
    {
        return CategoryType::Research;
    }

    protected function relations(): array
    {
        return ['researcher'];
    }
}
