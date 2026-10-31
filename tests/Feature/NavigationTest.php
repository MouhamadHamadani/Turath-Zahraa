<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_menu_entry_points_to_a_real_page(): void
    {
        $entries = collect(config('navigation'))->flatMap(fn (array $item) => $item['children'] ?? [$item]);

        $this->assertGreaterThanOrEqual(11, $entries->count());

        foreach ($entries as $entry) {
            $this->get(route($entry['route'], $entry['params'] ?? []))->assertOk();
        }
    }

    public function test_the_menu_renders_both_dropdowns_with_accessible_markup(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();

        $this->assertSame(2, substr_count($html, 'aria-haspopup="menu"'));
        $this->assertSame(2, substr_count($html, 'role="menu"'));
        $this->assertSame(8, substr_count($html, 'role="menuitem"'));
        $this->assertStringContainsString('aria-label="بحث"', $html);
        $this->assertStringContainsString('aria-controls="mobile-menu"', $html);
        $this->assertStringContainsString(':aria-expanded="open.toString()"', $html);

        foreach (['library.books', 'library.research', 'archive.audio', 'archive.videos', 'archive.photos', 'archive.documents'] as $route) {
            $this->assertStringContainsString('href="'.route($route).'"', $html);
        }

        $this->assertStringContainsString('href="'.route('library.publications', ['type' => 'special']).'"', $html);
    }

    public function test_a_dropdown_is_marked_active_when_one_of_its_children_is_current(): void
    {
        $html = $this->get(route('archive.photos'))->getContent();

        // The "الأرشيف" trigger and its "الصور" child are active; "المكتبة" is not.
        $this->assertMatchesRegularExpression('/aria-controls="nav-menu-d-[a-f0-9]+"[^>]*\s(?:class="[^"]*text-brand-second[^"]*")/s', $html);
        $this->assertSame(2, substr_count($html, 'aria-current="page"'), 'desktop and mobile copies of the الصور link');
    }

    public function test_publications_and_special_issues_are_never_both_active(): void
    {
        $special = $this->get(route('library.publications', ['type' => 'special']))->getContent();
        $plain = $this->get(route('library.publications'))->getContent();

        $specialUrl = preg_quote(route('library.publications', ['type' => 'special']), '/');
        $plainUrl = preg_quote(route('library.publications'), '/');

        $this->assertMatchesRegularExpression('/href="'.$specialUrl.'"[^>]*aria-current="page"/s', $special);
        $this->assertDoesNotMatchRegularExpression('/href="'.$plainUrl.'"[^>]*aria-current="page"/s', $special);

        $this->assertMatchesRegularExpression('/href="'.$plainUrl.'"[^>]*aria-current="page"/s', $plain);
        $this->assertDoesNotMatchRegularExpression('/href="'.$specialUrl.'"[^>]*aria-current="page"/s', $plain);
    }
}
