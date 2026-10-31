<?php

namespace Tests\Feature\Admin;

use App\Enums\CategoryType;
use App\Enums\ContentStatus;
use App\Filament\Resources\Books\Pages\CreateBook;
use App\Filament\Resources\Books\Pages\EditBook;
use App\Filament\Resources\Books\Pages\ListBooks;
use App\Filament\Resources\ContactMessages\Pages\ViewContactMessage;
use App\Filament\Resources\Videos\Pages\CreateVideo;
use App\Filament\Resources\Videos\VideoResource;
use App\Models\Book;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminResourcesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_creating_a_book_stores_one_category_list_per_type(): void
    {
        $topic = Category::factory()->ofType(CategoryType::HeritageTopic)->create();
        $bookCategory = Category::factory()->ofType(CategoryType::Book)->create();

        Livewire::test(CreateBook::class)
            ->fillForm([
                'title' => 'كتاب تجريبي',
                'slug' => 'كتاب-تجريبي',
                'language' => 'ar',
                'status' => ContentStatus::Published->value,
                'categories_heritage_topic' => [$topic->id],
                'categories_book' => [$bookCategory->id],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $book = Book::where('slug', 'كتاب-تجريبي')->firstOrFail();

        $this->assertEqualsCanonicalizing([$topic->id, $bookCategory->id], $book->categories()->pluck('categories.id')->all());
    }

    public function test_editing_one_category_list_leaves_the_other_untouched(): void
    {
        $topic = Category::factory()->ofType(CategoryType::HeritageTopic)->create();
        $bookCategory = Category::factory()->ofType(CategoryType::Book)->create();
        $book = Book::factory()->create();
        $book->categories()->attach([$topic->id, $bookCategory->id]);

        Livewire::test(EditBook::class, ['record' => $book->getKey()])
            ->assertFormSet(['categories_heritage_topic' => [$topic->id], 'categories_book' => [$bookCategory->id]])
            ->fillForm(['categories_heritage_topic' => []])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame([$bookCategory->id], $book->categories()->pluck('categories.id')->all());
    }

    public function test_a_cover_uploaded_in_the_form_lands_in_the_media_collection(): void
    {
        Storage::fake('public');

        Livewire::test(CreateBook::class)
            ->fillForm([
                'title' => 'كتاب بغلاف',
                'slug' => 'book-with-cover',
                'language' => 'ar',
                'status' => 'draft',
                'cover' => UploadedFile::fake()->image('cover.jpg', 600, 800),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $book = Book::where('slug', 'book-with-cover')->firstOrFail();

        $this->assertTrue($book->hasMedia('cover'));
        $this->assertStringContainsString('/storage/', $book->getFirstMediaUrl('cover'));
    }

    public function test_the_book_list_shows_books_and_filters_by_status(): void
    {
        $published = Book::factory()->published()->create();
        $draft = Book::factory()->draft()->create();

        Livewire::test(ListBooks::class)
            ->assertCanSeeTableRecords([$published, $draft])
            ->filterTable('status', ContentStatus::Draft->value)
            ->assertCanSeeTableRecords([$draft])
            ->assertCanNotSeeTableRecords([$published]);
    }

    public function test_slugs_must_be_unique(): void
    {
        Book::factory()->create(['slug' => 'taken']);

        Livewire::test(CreateBook::class)
            ->fillForm(['title' => 'عنوان', 'slug' => 'taken', 'language' => 'ar', 'status' => 'draft'])
            ->call('create')
            ->assertHasFormErrors(['slug' => 'unique']);
    }

    public function test_a_youtube_link_is_reduced_to_its_id(): void
    {
        $this->assertSame('dQw4w9WgXcQ', VideoResource::extractYoutubeId('https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=10'));
        $this->assertSame('dQw4w9WgXcQ', VideoResource::extractYoutubeId('https://youtu.be/dQw4w9WgXcQ'));
        $this->assertSame('dQw4w9WgXcQ', VideoResource::extractYoutubeId('dQw4w9WgXcQ'));
        $this->assertNull(VideoResource::extractYoutubeId('  '));
    }

    public function test_a_video_needs_a_valid_youtube_id(): void
    {
        Livewire::test(CreateVideo::class)
            ->fillForm(['title' => 'فيديو', 'slug' => 'video', 'youtube_id' => 'short', 'status' => 'draft'])
            ->call('create')
            ->assertHasFormErrors(['youtube_id' => 'regex']);

        Livewire::test(CreateVideo::class)
            ->fillForm(['title' => 'فيديو', 'slug' => 'video', 'youtube_id' => 'dQw4w9WgXcQ', 'status' => 'draft'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame('https://i.ytimg.com/vi/dQw4w9WgXcQ/hqdefault.jpg', Video::firstOrFail()->thumbnail_url);
    }

    public function test_contact_messages_can_be_marked_read_and_replied(): void
    {
        $message = ContactMessage::factory()->create();

        Livewire::test(ViewContactMessage::class, ['record' => $message->getKey()])
            ->callAction('markRead');

        $this->assertSame('read', $message->fresh()->status);
        $this->assertNotNull($message->fresh()->read_at);

        Livewire::test(ViewContactMessage::class, ['record' => $message->getKey()])
            ->callAction('markReplied');

        $this->assertSame('replied', $message->fresh()->status);
        $this->assertNotNull($message->fresh()->replied_at);
    }
}
