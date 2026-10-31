<?php

namespace App\Livewire;

use App\Models\AudioTrack;
use App\Models\Book;
use App\Models\Document;
use App\Models\Event;
use App\Models\NewsArticle;
use App\Models\Photo;
use App\Models\Publication;
use App\Models\Video;
use App\Settings\GeneralSettings;
use App\Settings\HomeStatsSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class Landing extends Component
{
    protected const COUNTS_CACHE_SECONDS = 300;

    protected const LATEST_LIMIT = 4;

    /**
     * Published counts, cached for a few minutes. Manual overrides from
     * HomeStatsSettings are applied afterwards so admin edits show at once.
     *
     * @return array{books: int, audio: int, videos: int, archive: int, events: int}
     */
    protected function counts(HomeStatsSettings $stats): array
    {
        $counts = Cache::remember('landing.counts', self::COUNTS_CACHE_SECONDS, fn () => [
            'books' => Book::published()->count() + Publication::published()->count(),
            'audio' => AudioTrack::published()->count(),
            'videos' => Video::published()->count(),
            'archive' => Photo::published()->count() + Document::published()->count(),
            'events' => Event::published()->count(),
        ]);

        return [
            'books' => $stats->books_count ?? $counts['books'],
            'audio' => $stats->audio_count ?? $counts['audio'],
            'videos' => $stats->videos_count ?? $counts['videos'],
            'archive' => $stats->archive_count ?? $counts['archive'],
            'events' => $stats->events_count ?? $counts['events'],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    protected function widgets(array $counts): array
    {
        return [
            ['title' => 'الكتب والإصدارات', 'description' => 'اكتشف الكتب والمطبوعات والإصدارات التي جمعتها المؤسسة ونشرتها.', 'url' => route('library.books'), 'icon' => 'fa-solid fa-book-open', 'value' => $counts['books']],
            ['title' => 'الصوتيات', 'description' => 'استمع إلى المحاضرات والخطب والمجالس والتسجيلات التاريخية.', 'url' => route('archive.audio'), 'icon' => 'fa-solid fa-headphones', 'value' => $counts['audio']],
            ['title' => 'الفيديوهات', 'description' => 'شاهد الندوات والبرامج والأفلام الوثائقية المختارة.', 'url' => route('archive.videos'), 'icon' => 'fa-solid fa-video', 'value' => $counts['videos']],
            ['title' => 'الصور والوثائق', 'description' => 'استعرض الصور والمخطوطات والوثائق التاريخية المؤرشفة.', 'url' => route('archive.photos'), 'icon' => 'fa-regular fa-images', 'value' => $counts['archive']],
            ['title' => 'البرامج والفعاليات', 'description' => 'تعرّف على برامج المؤسسة وفعالياتها القادمة والسابقة.', 'url' => route('news-events'), 'icon' => 'fa-solid fa-calendar-days', 'value' => $counts['events']],
        ];
    }

    /**
     * The newest events and news, mixed, newest first.
     *
     * @return Collection<int, array<string, mixed>>
     */
    protected function latest()
    {
        $events = Event::published()->with('media')->orderByDesc('starts_at')->limit(self::LATEST_LIMIT)->get()
            ->map(fn (Event $event) => [
                'title' => $event->title,
                'date' => $event->starts_at,
                'status' => $event->timeline_status,
                'image' => $event->getFirstMediaUrl('cover') ?: null,
            ]);

        $news = NewsArticle::latestPublished()->with('media')->limit(self::LATEST_LIMIT)->get()
            ->map(fn (NewsArticle $article) => [
                'title' => $article->title,
                'date' => $article->published_at ?? Date::parse($article->created_at),
                'status' => 'news',
                'image' => $article->getFirstMediaUrl('cover') ?: null,
            ]);

        return $events->concat($news)->sortByDesc('date')->take(self::LATEST_LIMIT)->values();
    }

    public function render(): View
    {
        $general = app(GeneralSettings::class);
        $stats = app(HomeStatsSettings::class);
        $counts = $this->counts($stats);

        return view('livewire.landing', [
            'general' => $general,
            'followers' => $stats->followers_count,
            'widgets' => $this->widgets($counts),
            'latest' => $this->latest(),
        ])->layoutData(['description' => $general->hero_subtitle]);
    }
}
