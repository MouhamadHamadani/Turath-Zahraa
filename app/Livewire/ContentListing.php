<?php

namespace App\Livewire;

use App\Enums\CategoryType;
use App\Livewire\Support\ContentCard;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * A paginated, searchable list of one published content type.
 * Subclasses only say which model, how a row becomes a card, and how to filter.
 */
abstract class ContentListing extends Component
{
    use WithPagination;

    /** @var class-string<Model> */
    protected string $model;

    protected string $icon = 'fa-solid fa-folder-open';

    protected int $perPage = 12;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    /** Category id, '' for all. */
    #[Url(except: '')]
    public string $category = '';

    abstract protected function heading(): string;

    abstract protected function description(): string;

    /** The category list shown as filter pills, or null for none. */
    protected function categoryType(): ?CategoryType
    {
        return null;
    }

    /** @return array<int, string> */
    protected function relations(): array
    {
        return [];
    }

    /** @return array<int, string> */
    protected function searchColumns(): array
    {
        return ['title'];
    }

    /**
     * Filter pills: label, the public property they set, and value => text options.
     *
     * @return array{label: string, property: string, options: array<int|string, string>}|null
     */
    protected function filter(): ?array
    {
        $type = $this->categoryType();

        if ($type === null) {
            return null;
        }

        return [
            'label' => 'التصنيف',
            'property' => 'category',
            'options' => Category::ofType($type)->pluck('name', 'id')->all(),
        ];
    }

    protected function query(): Builder
    {
        $term = '%'.addcslashes($this->search, '%_\\').'%';

        return $this->model::query()
            ->latestPublished()
            ->with(['media', ...$this->relations()])
            ->when($this->search !== '', fn (Builder $query) => $query->where(
                fn (Builder $q) => collect($this->searchColumns())->each(fn (string $column) => $q->orWhere($column, 'like', $term)),
            ))
            ->when($this->category !== '', fn (Builder $query) => $query->inCategory((int) $this->category));
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.content-listing', [
            'heading' => $this->heading(),
            'description' => $this->description(),
            'icon' => $this->icon,
            'filter' => $this->filter(),
            'cards' => $this->query()->paginate($this->perPage)->through(fn (Model $model) => ContentCard::make($model)),
        ])->title($this->heading())->layoutData(['description' => $this->description()]);
    }
}
