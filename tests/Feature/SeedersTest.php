<?php

namespace Tests\Feature;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Milestone;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\MilestoneSeeder;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class SeedersTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_category_seeder_creates_the_planned_lists_and_is_idempotent(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(CategorySeeder::class);

        $this->assertSame(10, Category::where('type', CategoryType::HeritageTopic->value)->count());
        $this->assertSame(6, Category::where('type', CategoryType::Audio->value)->count());
        $this->assertSame(8, Category::where('type', CategoryType::Video->value)->count());
        $this->assertSame(5, Category::where('type', CategoryType::Photo->value)->count());
        $this->assertSame(5, Category::where('type', CategoryType::Document->value)->count());
        $this->assertSame(5, Category::where('type', CategoryType::News->value)->count());
        $this->assertSame(39, Category::count());

        $this->assertSame('السيرة', Category::where(['type' => 'heritage_topic', 'slug' => 'sira'])->value('name'));
    }

    public function test_the_category_seeder_restores_names_and_keeps_ids_stable(): void
    {
        $this->seed(CategorySeeder::class);
        $category = Category::where(['type' => 'audio', 'slug' => 'lectures'])->firstOrFail();
        $category->update(['name' => 'تغيير']);

        $this->seed(CategorySeeder::class);

        $this->assertSame('المحاضرات', $category->fresh()->name);
        $this->assertSame($category->id, Category::where(['type' => 'audio', 'slug' => 'lectures'])->value('id'));
    }

    public function test_page_and_milestone_seeders_are_idempotent_and_keep_admin_edits(): void
    {
        Storage::fake('public');

        $this->seed([PageSeeder::class, MilestoneSeeder::class]);
        Page::where('slug', 'privacy')->update(['title' => 'عنوان عدّلته المؤسسة']);
        $this->seed([PageSeeder::class, MilestoneSeeder::class]);

        $this->assertSame(5, Page::count());
        $this->assertEqualsCanonicalizing(['vision', 'mission', 'privacy', 'terms', 'rights'], Page::pluck('slug')->all());
        $this->assertSame('عنوان عدّلته المؤسسة', Page::where('slug', 'privacy')->value('title'));
        $this->assertStringContainsString('مسودة', Page::where('slug', 'terms')->value('body'));

        $this->assertSame(3, Milestone::count());
        $this->assertSame([2020, 2021, 2022], Milestone::ordered()->pluck('year')->all());
        $this->assertTrue(Milestone::first()->hasMedia('image'));
        $this->assertSame(3, Media::count(), 'images are attached once, not on every run');
    }

    public function test_the_admin_seeder_refuses_to_run_without_credentials(): void
    {
        config(['app.admin.email' => null, 'app.admin.password' => null]);

        $this->expectException(RuntimeException::class);
        $this->seed(AdminUserSeeder::class);
    }

    public function test_the_admin_seeder_refuses_a_short_password(): void
    {
        config(['app.admin.email' => 'admin@example.test', 'app.admin.password' => 'short']);

        $this->expectException(RuntimeException::class);
        $this->seed(AdminUserSeeder::class);
    }

    public function test_the_admin_seeder_creates_one_admin_and_never_overwrites_the_password(): void
    {
        config(['app.admin.email' => 'admin@example.test', 'app.admin.password' => 'a-long-enough-password', 'app.admin.name' => 'مدير']);

        $this->seed(AdminUserSeeder::class);

        $admin = User::where('email', 'admin@example.test')->firstOrFail();
        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('a-long-enough-password', $admin->password));

        $admin->update(['password' => 'changed-by-the-admin']);
        config(['app.admin.password' => 'another-long-password']);
        $this->seed(AdminUserSeeder::class);

        $this->assertSame(1, User::count());
        $this->assertTrue(Hash::check('changed-by-the-admin', $admin->fresh()->password));
    }

    public function test_the_admin_seeder_promotes_an_existing_user(): void
    {
        $user = User::factory()->create(['email' => 'editor@example.test']);
        config(['app.admin.email' => 'editor@example.test', 'app.admin.password' => 'a-long-enough-password']);

        $this->seed(AdminUserSeeder::class);

        $this->assertTrue($user->fresh()->is_admin);
        $this->assertSame(1, User::count());
    }

    public function test_the_database_seeder_runs_twice_with_the_same_counts_and_no_test_user(): void
    {
        Storage::fake('public');
        config(['app.admin.email' => 'admin@example.test', 'app.admin.password' => 'a-long-enough-password']);

        $this->seed(DatabaseSeeder::class);
        $counts = [Category::count(), Page::count(), Milestone::count(), User::count()];
        $this->seed(DatabaseSeeder::class);

        $this->assertSame($counts, [Category::count(), Page::count(), Milestone::count(), User::count()]);
        $this->assertSame([39, 5, 3, 1], $counts);
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }
}
