<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Person;

/**
 * @extends ArabicFactory<Book>
 */
class BookFactory extends ArabicFactory
{
    public function definition(): array
    {
        $title = $this->title();

        return [
            'author_id' => Person::factory(),
            'title' => $title,
            'slug' => $this->slugFor($title),
            'summary' => $this->paragraph(),
            'pages_count' => $this->faker->numberBetween(60, 600),
            'published_year' => $this->faker->numberBetween(1995, 2025),
            'language' => 'ar',
            'is_downloadable' => false,
            'allow_online_reading' => false,
            'is_featured' => false,
            ...$this->publishedAttributes(),
        ];
    }
}
