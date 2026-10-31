<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, array{string}> */
    public static function adminPages(): array
    {
        $resources = ['people', 'categories', 'books', 'publications', 'research-papers', 'audio-tracks', 'videos', 'photos', 'documents', 'events', 'news-articles', 'articles', 'projects', 'pages', 'milestones'];

        $pages = ['/admin' => ['/admin']];

        foreach ($resources as $resource) {
            $pages["{$resource} index"] = ["/admin/{$resource}"];
            $pages["{$resource} create"] = ["/admin/{$resource}/create"];
        }

        $pages['contact-messages index'] = ['/admin/contact-messages'];

        foreach (['manage-general-settings', 'manage-contact-settings', 'manage-home-stats-settings'] as $page) {
            $pages[$page] = ["/admin/{$page}"];
        }

        return $pages;
    }

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect();
    }

    public function test_non_admin_users_are_blocked(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }

    #[DataProvider('adminPages')]
    public function test_admins_can_open_every_admin_page(string $url): void
    {
        $this->actingAs(User::factory()->admin()->create())->get($url)->assertOk();
    }

    public function test_the_admin_login_page_renders_in_arabic_rtl(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('dir="rtl"', false);
    }
}
