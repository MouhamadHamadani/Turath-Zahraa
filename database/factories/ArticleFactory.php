<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Person;

/**
 * @extends ArabicFactory<Article>
 */
class ArticleFactory extends ArabicFactory
{
    public function definition(): array
    {
        $title = $this->title();

        return [
            'author_id' => Person::factory(),
            'title' => $title,
            'slug' => $this->slugFor($title),
            'excerpt' => $this->sentence(),
            'body' => '<p>'.$this->paragraph().'</p>',
            'reading_minutes' => $this->faker->numberBetween(2, 15),
            'is_featured' => false,
            ...$this->publishedAttributes(),
        ];
    }
}
