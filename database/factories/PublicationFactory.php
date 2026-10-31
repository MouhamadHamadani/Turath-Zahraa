<?php

namespace Database\Factories;

use App\Enums\PublicationType;
use App\Models\Person;
use App\Models\Publication;

/**
 * @extends ArabicFactory<Publication>
 */
class PublicationFactory extends ArabicFactory
{
    public function definition(): array
    {
        $title = $this->title();

        return [
            'author_id' => Person::factory(),
            'type' => $this->faker->randomElement(PublicationType::cases()),
            'title' => $title,
            'slug' => $this->slugFor($title),
            'summary' => $this->paragraph(),
            'issue_number' => (string) $this->faker->numberBetween(1, 40),
            'pages_count' => $this->faker->numberBetween(20, 200),
            'published_year' => $this->faker->numberBetween(2005, 2025),
            'is_downloadable' => false,
            'allow_online_reading' => false,
            'is_featured' => false,
            ...$this->publishedAttributes(),
        ];
    }

    public function ofType(PublicationType $type): static
    {
        return $this->state(fn () => ['type' => $type]);
    }
}
