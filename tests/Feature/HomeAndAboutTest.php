<?php

namespace Tests\Feature;

use App\Livewire\Landing;
use App\Models\AudioTrack;
use App\Models\Book;
use App\Models\Event;
use App\Models\Milestone;
use App\Models\NewsArticle;
use App\Models\Page;
use App\Models\Photo;
use App\Models\Publication;
use App\Models\Video;
use App\Settings\GeneralSettings;
use App\Settings\HomeStatsSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HomeAndAboutTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<int, array<string, mixed>> */
    protected function widgets(): array
    {
        return Livewire::test(Landing::class)->viewData('widgets');
    }

    public function test_the_home_counts_come_from_published_content(): void
    {
        Book::factory()->count(3)->create();
        Publication::factory()->create();
        Book::factory()->draft()->create();
        AudioTrack::factory()->count(2)->create();
        Video::factory()->create();
        Photo::factory()->create();

        $values = collect($this->widgets())->pluck('value', 'title');

        $this->assertSame(4, $values['الكتب والإصدارات']);
        $this->assertSame(2, $values['الصوتيات']);
        $this->assertSame(1, $values['الفيديوهات']);
        $this->assertSame(1, $values['الصور والوثائق']);
        $this->assertSame(0, $values['البرامج والفعاليات']);
    }

    public function test_manual_overrides_win_and_null_means_count_from_the_database(): void
    {
        Book::factory()->count(2)->create();

        $stats = app(HomeStatsSettings::class);
        $stats->books_count = 1200;
        $stats->save();

        $values = collect($this->widgets())->pluck('value', 'title');
        $this->assertSame(1200, $values['الكتب والإصدارات']);
        $this->assertSame(0, $values['الصوتيات']);
    }

    public function test_the_widgets_link_to_real_routes(): void
    {
        $urls = collect($this->widgets())->pluck('url')->all();

        $this->assertSame([
            route('library.books'), route('archive.audio'), route('archive.videos'), route('archive.photos'), route('news-events'),
        ], $urls);
    }

    public function test_the_home_page_lists_the_newest_news_and_events_with_a_status_badge(): void
    {
        Event::factory()->upcoming()->create(['title' => 'ندوة قادمة']);
        Event::factory()->past()->create(['title' => 'ندوة منتهية', 'starts_at' => now()->subYear()]);
        NewsArticle::factory()->create(['title' => 'خبر حديث', 'published_at' => now()->subDay()]);
        NewsArticle::factory()->draft()->create(['title' => 'خبر مسودة']);

        $this->get(route('home'))
            ->assertSee('ندوة قادمة')
            ->assertSee('فعالية قادمة')
            ->assertSee('خبر حديث')
            ->assertDontSee('خبر مسودة');
    }

    public function test_the_home_page_has_an_empty_state_and_hides_the_followers_line_when_unset(): void
    {
        $stats = app(HomeStatsSettings::class);
        $stats->followers_count = null;
        $stats->save();

        $this->get(route('home'))
            ->assertSee('لا توجد أخبار أو فعاليات منشورة حتى الآن')
            ->assertDontSee('متابع على مواقع التواصل');
    }

    public function test_the_hero_text_and_archive_button_come_from_settings_and_routes(): void
    {
        $general = app(GeneralSettings::class);
        $general->hero_title = 'عنوان مخصص للواجهة';
        $general->save();

        $this->get(route('home'))
            ->assertSee('عنوان مخصص للواجهة')
            ->assertSee('href="'.route('archive.audio').'"', false);
    }

    public function test_the_about_page_is_built_from_milestones_pages_and_settings(): void
    {
        Milestone::factory()->create(['year' => 2023, 'title' => 'محطة مميزة', 'sort_order' => 2]);
        Milestone::factory()->create(['year' => 2019, 'title' => 'محطة أولى', 'sort_order' => 1]);
        Page::factory()->create(['slug' => 'vision', 'body' => '<p>رؤية المؤسسة المخصصة</p>']);

        $general = app(GeneralSettings::class);
        $general->goals = [['title' => 'هدف مخصص', 'description' => 'وصف الهدف المخصص']];
        $general->headquarters = 'كربلاء المقدسة';
        $general->save();

        $this->get(route('about-us'))
            ->assertOk()
            ->assertSeeInOrder(['محطة أولى', 'محطة مميزة'])
            ->assertSee('رؤية المؤسسة المخصصة')
            ->assertDontSee('>الرسالة<', false)
            ->assertSee('هدف مخصص')
            ->assertSee('كربلاء المقدسة');
    }

    public function test_the_about_page_never_renders_a_broken_image_for_a_milestone_without_media(): void
    {
        Milestone::factory()->create(['title' => 'بلا صورة']);

        $this->get(route('about-us'))
            ->assertSee('بلا صورة')
            ->assertDontSee('/images/about-us/timeline/', false);
    }
}
