<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\Video;
use Illuminate\Support\Str;

/**
 * @extends ArabicFactory<Video>
 */
class VideoFactory extends ArabicFactory
{
    public function definition(): array
    {
        $title = $this->title();

        return [
            'speaker_id' => Person::factory(),
            'title' => $title,
            'slug' => $this->slugFor($title),
            'youtube_id' => Str::random(11),
            'description' => $this->paragraph(),
            'recorded_at' => $this->faker->dateTimeBetween('-10 years', '-1 month'),
            'duration_seconds' => $this->faker->numberBetween(300, 5400),
            'is_featured' => false,
            ...$this->publishedAttributes(),
        ];
    }
}
