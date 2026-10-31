<?php

namespace Tests\Feature\Admin;

use App\Enums\CategoryType;
use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\AudioTracks\Pages\CreateAudioTrack;
use App\Filament\Resources\AudioTracks\Pages\EditAudioTrack;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Documents\Pages\CreateDocument;
use App\Filament\Resources\Documents\Pages\EditDocument;
use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Milestones\Pages\CreateMilestone;
use App\Filament\Resources\Milestones\Pages\EditMilestone;
use App\Filament\Resources\NewsArticles\Pages\CreateNewsArticle;
use App\Filament\Resources\NewsArticles\Pages\EditNewsArticle;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\People\Pages\CreatePerson;
use App\Filament\Resources\People\Pages\EditPerson;
use App\Filament\Resources\Photos\Pages\CreatePhoto;
use App\Filament\Resources\Photos\Pages\EditPhoto;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Publications\Pages\CreatePublication;
use App\Filament\Resources\Publications\Pages\EditPublication;
use App\Filament\Resources\ResearchPapers\Pages\CreateResearchPaper;
use App\Filament\Resources\ResearchPapers\Pages\EditResearchPaper;
use App\Models\Article;
use App\Models\AudioTrack;
use App\Models\Category;
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
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * One create + edit cycle per resource that AdminResourcesTest does not already cover (Book, Video).
 */
class ResourceCycleTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, array{class-string, class-string, class-string, array<string, mixed>}> */
    public static function resources(): array
    {
        $slug = ['slug' => 'عنصر-تجريبي'];
        $published = ['status' => 'published'];

        return [
            'person' => [CreatePerson::class, EditPerson::class, Person::class, ['name' => 'الشيخ تجريبي', ...$slug]],
            'category' => [CreateCategory::class, EditCategory::class, Category::class, ['type' => CategoryType::Audio->value, 'name' => 'تصنيف', ...$slug]],
            'publication' => [CreatePublication::class, EditPublication::class, Publication::class, ['type' => 'magazine', 'title' => 'مجلة', ...$slug, ...$published]],
            'research' => [CreateResearchPaper::class, EditResearchPaper::class, ResearchPaper::class, ['title' => 'بحث', ...$slug, ...$published]],
            'audio' => [CreateAudioTrack::class, EditAudioTrack::class, AudioTrack::class, ['title' => 'محاضرة', ...$slug, ...$published]],
            'document' => [CreateDocument::class, EditDocument::class, Document::class, ['title' => 'وثيقة', ...$slug, ...$published]],
            'event' => [CreateEvent::class, EditEvent::class, Event::class, ['title' => 'ندوة', ...$slug, 'venue' => 'القاعة', 'starts_at' => '2030-01-01 10:00:00', ...$published]],
            'news' => [CreateNewsArticle::class, EditNewsArticle::class, NewsArticle::class, ['title' => 'خبر', ...$slug, 'body' => '<p>نص الخبر</p>', ...$published]],
            'article' => [CreateArticle::class, EditArticle::class, Article::class, ['title' => 'مقال', ...$slug, 'body' => '<p>نص المقال</p>', ...$published]],
            'project' => [CreateProject::class, EditProject::class, Project::class, ['name' => 'مشروع', ...$slug, 'status' => 'ongoing']],
            'page' => [CreatePage::class, EditPage::class, Page::class, ['title' => 'صفحة', ...$slug, 'status' => 'published']],
            'milestone' => [CreateMilestone::class, EditMilestone::class, Milestone::class, ['year' => 2024, 'title' => 'محطة']],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    #[DataProvider('resources')]
    public function test_a_record_can_be_created_and_then_edited(string $createPage, string $editPage, string $model, array $data): void
    {
        Livewire::test($createPage)->fillForm($data)->call('create')->assertHasNoFormErrors();

        $record = $model::query()->latest('id')->firstOrFail();

        Livewire::test($editPage, ['record' => $record->getKey()])
            ->assertFormSet(array_intersect_key($data, ['slug' => 1, 'title' => 1, 'name' => 1]))
            ->call('save')
            ->assertHasNoFormErrors();
    }

    public function test_a_photo_needs_an_image_and_stores_it(): void
    {
        Storage::fake('public');

        Livewire::test(CreatePhoto::class)->fillForm(['title' => 'صورة', 'status' => 'published'])->call('create')->assertHasFormErrors(['image' => 'required']);

        Livewire::test(CreatePhoto::class)
            ->fillForm(['title' => 'صورة', 'status' => 'published', 'image' => UploadedFile::fake()->image('p.jpg', 800, 600)])
            ->call('create')
            ->assertHasNoFormErrors();

        $photo = Photo::firstOrFail();
        $this->assertTrue($photo->hasMedia('image'));

        Livewire::test(EditPhoto::class, ['record' => $photo->getKey()])->call('save')->assertHasNoFormErrors();
    }

    public function test_an_event_cannot_end_before_it_starts(): void
    {
        Livewire::test(CreateEvent::class)
            ->fillForm(['title' => 'ندوة', 'slug' => 'ندوة', 'venue' => 'القاعة', 'starts_at' => '2030-01-02 10:00:00', 'ends_at' => '2030-01-01 10:00:00', 'status' => 'draft'])
            ->call('create')
            ->assertHasFormErrors(['ends_at']);
    }
}
