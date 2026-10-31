<?php

namespace App\Livewire\Library;

use App\Enums\CategoryType;
use App\Livewire\ContentListing;
use App\Models\Book;

class Books extends ContentListing
{
    protected string $model = Book::class;

    protected string $icon = 'fa-solid fa-book-open';

    protected function heading(): string
    {
        return 'الكتب';
    }

    protected function description(): string
    {
        return 'مكتبة الكتب التي جمعتها المؤسسة ونشرتها في التراث الفاطمي.';
    }

    protected function categoryType(): CategoryType
    {
        return CategoryType::Book;
    }

    protected function relations(): array
    {
        return ['author'];
    }
}
