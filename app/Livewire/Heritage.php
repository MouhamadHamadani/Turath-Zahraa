<?php

namespace App\Livewire;

use App\Enums\CategoryType;
use App\Livewire\Support\ContentCard;
use App\Models\AudioTrack;
use App\Models\Book;
use App\Models\Category;
use App\Models\Document;
use App\Models\Photo;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class Heritage extends Component
{
    /** Heritage topic category id, '' for every topic. */
    #[Url(as: 'topic', except: '')]
    public string $topic = '';

    /**
     * What the page pulls from: label, model, relations to eager load, icon, "see all" route.
     *
     * @var array<int, array{label: string, model: class-string, with: array<int, string>, icon: string, route: string}>
     */
    protected const SECTIONS = [
        ['label' => 'الكتب', 'model' => Book::class, 'with' => ['author'], 'icon' => 'fa-solid fa-book-open', 'route' => 'library.books'],
        ['label' => 'الصوتيات', 'model' => AudioTrack::class, 'with' => ['speaker'], 'icon' => 'fa-solid fa-headphones', 'route' => 'archive.audio'],
        ['label' => 'الفيديوهات', 'model' => Video::class, 'with' => ['speaker'], 'icon' => 'fa-solid fa-video', 'route' => 'archive.videos'],
        ['label' => 'الصور', 'model' => Photo::class, 'with' => [], 'icon' => 'fa-regular fa-images', 'route' => 'archive.photos'],
        ['label' => 'الوثائق', 'model' => Document::class, 'with' => [], 'icon' => 'fa-solid fa-scroll', 'route' => 'archive.documents'],
    ];

    protected const PER_SECTION = 8;

    public function render(): View
    {
        $topics = Category::ofType(CategoryType::HeritageTopic)->get();
        $selected = $topics->firstWhere('id', (int) $this->topic);

        $sections = collect(self::SECTIONS)
            ->map(fn (array $section) => $section + ['items' => $section['model']::query()
                ->latestPublished()
                ->with(['media', ...$section['with']])
                ->when(
                    $selected,
                    fn ($query) => $query->inCategory($selected),
                    fn ($query) => $query->whereHas('categories', fn ($categories) => $categories->where('type', CategoryType::HeritageTopic->value)),
                )
                ->limit(self::PER_SECTION)
                ->get()
                ->map(fn ($model) => ContentCard::make($model)),
            ])
            ->filter(fn (array $section) => $section['items']->isNotEmpty());

        return view('livewire.heritage', compact('topics', 'selected', 'sections'))
            ->title($selected ? "تراث السيدة الزهراء: {$selected->name}" : 'تراث السيدة الزهراء')
            ->layoutData(['description' => 'كتب وصوتيات ومرئيات وصور ووثائق في سيرة الصديقة الزهراء عليها السلام وروايتها وخطبها ومواقفها.']);
    }
}
