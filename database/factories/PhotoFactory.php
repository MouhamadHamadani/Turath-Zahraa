<?php

namespace Database\Factories;

use App\Models\Photo;

/**
 * @extends ArabicFactory<Photo>
 */
class PhotoFactory extends ArabicFactory
{
    public function definition(): array
    {
        return [
            'title' => $this->title(),
            'caption' => $this->sentence(),
            'credit' => $this->faker->name(),
            'taken_at' => $this->faker->dateTimeBetween('-10 years', '-1 month'),
            'year' => $this->faker->numberBetween(2010, 2025),
            'is_featured' => false,
            'sort_order' => 0,
            ...$this->publishedAttributes(),
        ];
    }
}
