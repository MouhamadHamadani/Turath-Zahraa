<?php

namespace App\Livewire\Library;

use App\Enums\PublicationType;
use App\Livewire\ContentListing;
use App\Models\Publication;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class Publications extends ContentListing
{
    protected string $model = Publication::class;

    protected string $icon = 'fa-solid fa-newspaper';

    /** A PublicationType value, '' for all. The "الإصدارات" menu entry links here with ?type=special. */
    #[Url(except: '')]
    public string $type = '';

    protected function heading(): string
    {
        return $this->type === PublicationType::Special->value ? 'الإصدارات' : 'المطبوعات';
    }

    protected function description(): string
    {
        return 'المجلات والكتيبات والنشرات والإصدارات الخاصة التي تصدرها المؤسسة.';
    }

    protected function relations(): array
    {
        return ['author'];
    }

    protected function filter(): array
    {
        return [
            'label' => 'النوع',
            'property' => 'type',
            'options' => collect(PublicationType::cases())->mapWithKeys(fn (PublicationType $type) => [$type->value => $type->getLabel()])->all(),
        ];
    }

    protected function query(): Builder
    {
        return parent::query()->when(
            PublicationType::tryFrom($this->type),
            fn (Builder $query, PublicationType $type) => $query->where('type', $type->value),
        );
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }
}
