<?php

namespace App\Livewire;

use App\Livewire\Support\ContentCard;
use App\Models\Article;
use App\Models\AudioTrack;
use App\Models\Book;
use App\Models\Document;
use App\Models\NewsArticle;
use App\Models\Photo;
use App\Models\Publication;
use App\Models\ResearchPaper;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Basic cross-model search: title LIKE across the taggable models.
 * ponytail: LIKE scans, fine for thousands of rows. Move to Laravel Scout (Meilisearch/Typesense) when it slows down.
 */
class Search extends Component
{
    protected const MIN_LENGTH = 2;

    protected const PER_GROUP = 6;

    #[Url(as: 'q', except: '')]
    public string $q = '';

    /**
     * Group label, model, relations, columns searched, icon, and the section page that lists them all.
     *
     * @var array<int, array{label: string, model: class-string, with: array<int, string>, columns: array<int, string>, icon: string, route: string}>
     */
    protected const GROUPS = [
        ['label' => 'الكتب', 'model' => Book::class, 'with' => ['author'], 'columns' => ['title'], 'icon' => 'fa-solid fa-book-open', 'route' => 'library.books'],
        ['label' => 'البحوث', 'model' => ResearchPaper::class, 'with' => ['researcher'], 'columns' => ['title'], 'icon' => 'fa-solid fa-graduation-cap', 'route' => 'library.research'],
        ['label' => 'المطبوعات', 'model' => Publication::class, 'with' => ['author'], 'columns' => ['title'], 'icon' => 'fa-solid fa-newspaper', 'route' => 'library.publications'],
        ['label' => 'الصوتيات', 'model' => AudioTrack::class, 'with' => ['speaker'], 'columns' => ['title'], 'icon' => 'fa-solid fa-headphones', 'route' => 'archive.audio'],
        ['label' => 'الفيديوهات', 'model' => Video::class, 'with' => ['speaker'], 'columns' => ['title'], 'icon' => 'fa-solid fa-video', 'route' => 'archive.videos'],
        ['label' => 'الصور', 'model' => Photo::class, 'with' => [], 'columns' => ['title', 'caption'], 'icon' => 'fa-regular fa-images', 'route' => 'archive.photos'],
        ['label' => 'الوثائق', 'model' => Document::class, 'with' => [], 'columns' => ['title'], 'icon' => 'fa-solid fa-scroll', 'route' => 'archive.documents'],
        ['label' => 'الأخبار', 'model' => NewsArticle::class, 'with' => [], 'columns' => ['title'], 'icon' => 'fa-regular fa-newspaper', 'route' => 'news-events'],
        ['label' => 'المقالات', 'model' => Article::class, 'with' => ['author'], 'columns' => ['title'], 'icon' => 'fa-solid fa-pen-nib', 'route' => 'news-events'],
    ];

    public function render(): View
    {
        $term = trim($this->q);
        $searchable = mb_strlen($term) >= self::MIN_LENGTH;

        $groups = $searchable ? $this->search($term) : collect();

        return view('livewire.search', ['term' => $term, 'searchable' => $searchable, 'groups' => $groups])
            ->title($searchable ? "نتائج البحث: {$term}" : 'البحث')
            ->layoutData(['description' => 'ابحث في كتب المؤسسة وبحوثها وأرشيفها الصوتي والمرئي ووثائقها وأخبارها.']);
    }

    /** @return Collection<int, array<string, mixed>> */
    protected function search(string $term)
    {
        $like = '%'.addcslashes($term, '%_\\').'%';

        return collect(self::GROUPS)
            ->map(fn (array $group) => $group + ['items' => $group['model']::query()
                ->latestPublished()
                ->with(['media', ...$group['with']])
                ->where(fn ($query) => collect($group['columns'])->each(fn (string $column) => $query->orWhere($column, 'like', $like)))
                ->limit(self::PER_GROUP)
                ->get()
                ->map(fn ($model) => ContentCard::make($model)),
            ])
            ->filter(fn (array $group) => $group['items']->isNotEmpty())
            ->values();
    }
}
