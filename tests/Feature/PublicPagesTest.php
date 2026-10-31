<?php

namespace Tests\Feature;

use App\Enums\CategoryType;
use App\Enums\PublicationType;
use App\Models\Article;
use App\Models\AudioTrack;
use App\Models\Book;
use App\Models\Category;
use App\Models\Document;
use App\Models\Event;
use App\Models\NewsArticle;
use App\Models\Page;
use App\Models\Photo;
use App\Models\Publication;
use App\Models\ResearchPaper;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, array{string}> */
    public static function routeNames(): array
    {
        return collect(['home', 'about-us', 'heritage', 'library.books', 'library.research', 'library.publications', 'archive.audio', 'archive.videos', 'archive.photos', 'archive.documents', 'news-events', 'contact', 'search'])
            ->mapWithKeys(fn (string $name) => [$name => [$name]])
            ->all();
    }

    #[DataProvider('routeNames')]
    public function test_every_named_page_renders_when_the_database_is_empty(string $name): void
    {
        $this->get(route($name))->assertOk();
    }

    #[DataProvider('routeNames')]
    public function test_every_named_page_renders_with_content(string $name): void
    {
        $this->seedContent();

        $this->get(route($name))->assertOk();
    }

    public function test_listing_pages_show_published_items_and_hide_drafts_and_scheduled_ones(): void
    {
        $visible = Book::factory()->published()->create(['title' => 'كتاب ظاهر']);
        $draft = Book::factory()->draft()->create(['title' => 'كتاب مسودة']);
        $scheduled = Book::factory()->scheduled()->create(['title' => 'كتاب مجدول']);

        $this->get(route('library.books'))
            ->assertSee($visible->title)
            ->assertDontSee($draft->title)
            ->assertDontSee($scheduled->title);
    }

    public function test_an_empty_section_shows_an_arabic_empty_state(): void
    {
        $this->get(route('archive.audio'))->assertSee('لا يوجد محتوى منشور في هذا القسم حتى الآن');
    }

    public function test_publications_can_be_filtered_by_type_from_the_menu_link(): void
    {
        Publication::factory()->ofType(PublicationType::Special)->create(['title' => 'إصدار خاص جدا']);
        Publication::factory()->ofType(PublicationType::Magazine)->create(['title' => 'مجلة عادية']);

        $this->get(route('library.publications', ['type' => 'special']))
            ->assertSee('إصدار خاص جدا')
            ->assertDontSee('مجلة عادية');
    }

    public function test_the_heritage_page_filters_by_topic(): void
    {
        $sira = Category::factory()->ofType(CategoryType::HeritageTopic)->create(['name' => 'السيرة']);
        $sermons = Category::factory()->ofType(CategoryType::HeritageTopic)->create(['name' => 'الخطب']);

        $inSira = Book::factory()->create(['title' => 'كتاب في السيرة']);
        $inSira->categories()->attach($sira);
        $inSermons = Video::factory()->create(['title' => 'فيديو في الخطب']);
        $inSermons->categories()->attach($sermons);

        $this->get(route('heritage', ['topic' => $sira->id]))
            ->assertSee('كتاب في السيرة')
            ->assertDontSee('فيديو في الخطب');

        $this->get(route('heritage'))->assertSee('كتاب في السيرة')->assertSee('فيديو في الخطب');
    }

    public function test_search_groups_results_by_type_and_ignores_drafts(): void
    {
        Book::factory()->create(['title' => 'فاطمة الزهراء في الكتب']);
        Video::factory()->create(['title' => 'محاضرة عن فاطمة']);
        Book::factory()->draft()->create(['title' => 'فاطمة المسودة']);

        $this->get(route('search', ['q' => 'فاطمة']))
            ->assertSee('فاطمة الزهراء في الكتب')
            ->assertSee('محاضرة عن فاطمة')
            ->assertDontSee('فاطمة المسودة');

        $this->get(route('search', ['q' => 'ف']))->assertSee('اكتب حرفين على الأقل');
    }

    public function test_static_pages_render_only_when_published(): void
    {
        Page::factory()->create(['slug' => 'privacy', 'title' => 'سياسة الخصوصية', 'body' => '<p>نص الخصوصية</p>']);
        Page::factory()->create(['slug' => 'secret', 'status' => 'draft']);

        $this->get(route('page.show', 'privacy'))->assertOk()->assertSee('نص الخصوصية', false);
        $this->get(route('page.show', 'secret'))->assertNotFound();
        $this->get(route('page.show', 'missing'))->assertNotFound();
    }

    public function test_the_layout_sets_title_description_and_open_graph_tags(): void
    {
        $this->get(route('contact'))
            ->assertSee('<title>تواصل معنا | ', false)
            ->assertSee('<meta name="description"', false)
            ->assertSee('<meta property="og:title"', false)
            ->assertSee('<link rel="canonical" href="'.route('contact').'"', false);
    }

    public function test_the_footer_links_to_legal_pages_that_exist_and_hides_contact_details_that_are_unset(): void
    {
        Page::factory()->create(['slug' => 'terms', 'title' => 'شروط الاستخدام']);

        $this->get(route('home'))
            ->assertSee(route('page.show', 'terms'), false)
            ->assertDontSee(route('page.show', 'privacy'), false)
            ->assertDontSee('href="tel:', false)
            ->assertDontSee('href="mailto:', false);
    }

    protected function seedContent(): void
    {
        Book::factory()->count(2)->create();
        Publication::factory()->create();
        ResearchPaper::factory()->create();
        AudioTrack::factory()->create();
        Video::factory()->create();
        Photo::factory()->create();
        Document::factory()->create();
        NewsArticle::factory()->create();
        Article::factory()->create();
        Event::factory()->upcoming()->create();
        Event::factory()->past()->create();
        Category::factory()->ofType(CategoryType::HeritageTopic)->create();
    }
}
