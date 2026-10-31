<?php

namespace Tests\Feature;

use App\Enums\CategoryType;
use App\Enums\ContentStatus;
use App\Models\Article;
use App\Models\AudioTrack;
use App\Models\Book;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Document;
use App\Models\Event;
use App\Models\Milestone;
use App\Models\NewsArticle;
use App\Models\Page;
use App\Models\Person;
use App\Models\Photo;
use App\Models\Project;
use App\Models\Publication;
use App\Models\ResearchPaper;
use App\Models\Video;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContentModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_hides_drafts_archived_and_future_dated_items(): void
    {
        $visible = Book::factory()->published()->create();
        $noDate = Book::factory()->create(['status' => ContentStatus::Published, 'published_at' => null]);
        Book::factory()->draft()->create();
        Book::factory()->scheduled()->create();
        Book::factory()->create(['status' => ContentStatus::Archived]);

        $this->assertEqualsCanonicalizing([$visible->id, $noDate->id], Book::published()->pluck('id')->all());
    }

    public function test_latest_published_orders_newest_first(): void
    {
        $old = Book::factory()->create(['published_at' => now()->subDays(10)]);
        $new = Book::factory()->create(['published_at' => now()->subDay()]);

        $this->assertSame([$new->id, $old->id], Book::latestPublished()->pluck('id')->all());
    }

    public function test_in_category_matches_only_items_tagged_with_it(): void
    {
        $sira = Category::factory()->create();
        $other = Category::factory()->create();
        $tagged = Book::factory()->create();
        $tagged->categories()->attach($sira);
        Book::factory()->create()->categories()->attach($other);
        Book::factory()->create();

        $this->assertSame([$tagged->id], Book::inCategory($sira)->pluck('id')->all());
        $this->assertSame([$tagged->id], Book::inCategory($sira->id)->pluck('id')->all());
    }

    public function test_one_item_can_carry_categories_of_several_types(): void
    {
        $topic = Category::factory()->ofType(CategoryType::HeritageTopic)->create();
        $audio = Category::factory()->ofType(CategoryType::Audio)->create();
        $track = AudioTrack::factory()->create();
        $track->categories()->attach([$topic->id, $audio->id]);

        $this->assertSame([$topic->id], $track->categoriesOfType(CategoryType::HeritageTopic)->pluck('categories.id')->all());
        $this->assertSame([$audio->id], $track->categoriesOfType(CategoryType::Audio)->pluck('categories.id')->all());
        $this->assertSame([$track->id], $topic->audioTracks()->pluck('audio_tracks.id')->all());
    }

    public function test_syncing_one_category_type_keeps_the_others(): void
    {
        [$topicA, $topicB] = Category::factory()->count(2)->ofType(CategoryType::HeritageTopic)->create();
        $audio = Category::factory()->ofType(CategoryType::Audio)->create();
        $track = AudioTrack::factory()->create();
        $track->categories()->attach([$topicA->id, $audio->id]);

        $track->syncCategoriesOfType(CategoryType::HeritageTopic, [$topicB->id]);

        $this->assertEqualsCanonicalizing([$topicB->id, $audio->id], $track->categories()->pluck('categories.id')->all());
    }

    public function test_category_of_type_returns_active_categories_in_order(): void
    {
        Category::factory()->ofType(CategoryType::News)->create(['name' => 'ثاني', 'sort_order' => 2]);
        Category::factory()->ofType(CategoryType::News)->create(['name' => 'أول', 'sort_order' => 1]);
        Category::factory()->ofType(CategoryType::News)->inactive()->create(['name' => 'مخفي']);
        Category::factory()->ofType(CategoryType::Audio)->create(['name' => 'صوتي']);

        $this->assertSame(['أول', 'ثاني'], Category::ofType(CategoryType::News)->pluck('name')->all());
    }

    public function test_category_slugs_are_unique_per_type_only(): void
    {
        Category::factory()->ofType(CategoryType::Audio)->create(['slug' => 'lectures']);
        Category::factory()->ofType(CategoryType::Video)->create(['slug' => 'lectures']);

        $this->assertDatabaseCount('categories', 2);

        $this->expectException(QueryException::class);
        Category::factory()->ofType(CategoryType::Video)->create(['slug' => 'lectures']);
    }

    public function test_events_are_classified_by_their_dates(): void
    {
        $upcoming = Event::factory()->create(['starts_at' => now()->addDays(5), 'ends_at' => null]);
        $ongoing = Event::factory()->create(['starts_at' => now()->subDay(), 'ends_at' => now()->addDay()]);
        $past = Event::factory()->create(['starts_at' => now()->subDays(5), 'ends_at' => now()->subDays(4)]);
        $pastNoEnd = Event::factory()->create(['starts_at' => now()->subDays(9), 'ends_at' => null]);

        $this->assertSame('upcoming', $upcoming->timeline_status);
        $this->assertSame('ongoing', $ongoing->timeline_status);
        $this->assertSame('past', $past->timeline_status);

        $this->assertEqualsCanonicalizing([$upcoming->id, $ongoing->id], Event::upcoming()->pluck('id')->all());
        $this->assertEqualsCanonicalizing([$past->id, $pastNoEnd->id], Event::past()->pluck('id')->all());
    }

    public function test_a_video_thumbnail_falls_back_to_youtube_and_prefers_an_uploaded_one(): void
    {
        Storage::fake('public');
        $video = Video::factory()->create(['youtube_id' => 'dQw4w9WgXcQ']);

        $this->assertSame('https://i.ytimg.com/vi/dQw4w9WgXcQ/hqdefault.jpg', $video->thumbnail_url);

        $video->addMedia(UploadedFile::fake()->image('thumb.jpg'))->toMediaCollection('thumbnail');

        $this->assertStringContainsString('/storage/', $video->fresh()->thumbnail_url);
        $this->assertStringNotContainsString('ytimg.com', $video->fresh()->thumbnail_url);
    }

    public function test_audio_files_use_the_configured_audio_disk(): void
    {
        config(['filesystems.audio_disk' => 'audio-test']);
        config(['filesystems.disks.audio-test' => ['driver' => 'local', 'root' => storage_path('framework/testing/disks/audio-test')]]);
        Storage::fake('audio-test');

        $track = AudioTrack::factory()->create();
        $collection = $track->getRegisteredMediaCollections()->firstWhere('name', 'audio');

        $this->assertSame('audio-test', $collection->diskName);
    }

    /** @return array<string, array{class-string, string}> */
    public static function modelsWithPublishDates(): array
    {
        return [
            'book' => [Book::class, 'published_at'],
            'news' => [NewsArticle::class, 'published_at'],
            'photo' => [Photo::class, 'published_at'],
        ];
    }

    #[DataProvider('modelsWithPublishDates')]
    public function test_publish_dates_are_cast_to_carbon(string $model, string $column): void
    {
        $this->assertInstanceOf(Carbon::class, $model::factory()->published()->create()->{$column});
    }

    public function test_every_content_factory_builds_a_valid_published_record_and_a_draft_state(): void
    {
        $factories = [
            Person::class, Category::class, Book::class, Publication::class,
            ResearchPaper::class, AudioTrack::class, Video::class, Photo::class,
            Document::class, Event::class, NewsArticle::class, Article::class,
            Project::class, Page::class, Milestone::class, ContactMessage::class,
        ];

        foreach ($factories as $model) {
            $this->assertTrue($model::factory()->create()->exists, "{$model} factory");
        }

        foreach ([Book::class, Video::class, Photo::class, Event::class] as $model) {
            $this->assertSame(ContentStatus::Draft, $model::factory()->draft()->create()->status);
        }
    }
}
