<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Milestone;
use App\Models\Page;
use App\Models\Publication;
use App\Settings\GeneralSettings;
use App\Settings\HomeStatsSettings;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class AboutUs extends Component
{
    /**
     * The small figures under the intro. A figure with no value is left out.
     *
     * @return array<int, array{title: string, value: string}>
     */
    protected function info(GeneralSettings $general, HomeStatsSettings $stats): array
    {
        $books = $stats->books_count ?? Book::published()->count() + Publication::published()->count();

        return array_values(array_filter([
            $stats->followers_count ? ['title' => 'متابع على التواصل', 'value' => number_format($stats->followers_count).'+'] : null,
            $books ? ['title' => 'كتاب وإصدار موثّق', 'value' => number_format($books)] : null,
            filled($general->headquarters) ? ['title' => 'مقر المؤسسة', 'value' => $general->headquarters] : null,
        ]));
    }

    /** The renderer sanitizes the editor's HTML. */
    protected function rich(?string $body): ?HtmlString
    {
        return filled($body) ? new HtmlString(RichContentRenderer::make($body)->toHtml()) : null;
    }

    public function render(): View
    {
        $general = app(GeneralSettings::class);
        $pages = Page::published()->whereIn('slug', ['vision', 'mission'])->pluck('body', 'slug');

        return view('livewire.about-us', [
            'general' => $general,
            'info' => $this->info($general, app(HomeStatsSettings::class)),
            'vision' => $this->rich($pages['vision'] ?? null),
            'mission' => $this->rich($pages['mission'] ?? null),
            'timeline' => Milestone::ordered()->with('media')->get(),
        ])->title('عن المؤسسة')->layoutData(['description' => $general->about_summary]);
    }
}
