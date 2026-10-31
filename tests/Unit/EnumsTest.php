<?php

namespace Tests\Unit;

use App\Enums\CategoryType;
use App\Enums\ContentStatus;
use App\Enums\ProjectStatus;
use App\Enums\PublicationType;
use App\Models\Book;
use App\Models\Category;
use App\Models\Project;
use App\Models\Publication;
use PHPUnit\Framework\TestCase;

class EnumsTest extends TestCase
{
    public function test_models_cast_their_columns_to_enums(): void
    {
        $this->assertSame(ContentStatus::Published, (new Book(['status' => 'published']))->status);
        $this->assertSame(PublicationType::Special, (new Publication(['type' => 'special']))->type);
        $this->assertSame(CategoryType::HeritageTopic, (new Category(['type' => 'heritage_topic']))->type);
        $this->assertSame(ProjectStatus::Ongoing, (new Project(['status' => 'ongoing']))->status);
    }

    public function test_an_unknown_value_is_rejected(): void
    {
        $this->expectException(\ValueError::class);

        (new Book(['status' => 'deleted']))->status;
    }

    public function test_every_case_has_an_arabic_label(): void
    {
        foreach ([ContentStatus::class, PublicationType::class, CategoryType::class, ProjectStatus::class] as $enum) {
            foreach ($enum::cases() as $case) {
                $this->assertMatchesRegularExpression('/\p{Arabic}/u', $case->getLabel(), "{$enum}::{$case->name}");
            }
        }
    }

    public function test_content_status_colors(): void
    {
        $this->assertSame('success', ContentStatus::Published->getColor());
        $this->assertSame('gray', ContentStatus::Draft->getColor());
    }
}
