<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\ResearchPaper;

/**
 * @extends ArabicFactory<ResearchPaper>
 */
class ResearchPaperFactory extends ArabicFactory
{
    public function definition(): array
    {
        $title = $this->title();

        return [
            'researcher_id' => Person::factory(),
            'title' => $title,
            'slug' => $this->slugFor($title),
            'abstract' => $this->paragraph(),
            'pages_count' => $this->faker->numberBetween(10, 90),
            'published_year' => $this->faker->numberBetween(2005, 2025),
            'is_downloadable' => true,
            ...$this->publishedAttributes(),
        ];
    }
}
