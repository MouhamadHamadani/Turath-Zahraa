<?php

namespace Database\Factories;

use App\Models\AudioTrack;
use App\Models\Person;

/**
 * @extends ArabicFactory<AudioTrack>
 */
class AudioTrackFactory extends ArabicFactory
{
    public function definition(): array
    {
        $title = $this->title();

        return [
            'speaker_id' => Person::factory(),
            'title' => $title,
            'slug' => $this->slugFor($title),
            'description' => $this->paragraph(),
            'recorded_at' => $this->faker->dateTimeBetween('-10 years', '-1 month'),
            'duration_seconds' => $this->faker->numberBetween(300, 5400),
            'is_downloadable' => false,
            'is_featured' => false,
            ...$this->publishedAttributes(),
        ];
    }
}
