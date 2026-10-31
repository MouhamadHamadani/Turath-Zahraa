<?php

namespace Database\Factories;

use App\Models\Event;

/**
 * @extends ArabicFactory<Event>
 */
class EventFactory extends ArabicFactory
{
    public function definition(): array
    {
        $title = $this->title();
        $startsAt = $this->faker->dateTimeBetween('-1 year', '+6 months');

        return [
            'title' => $title,
            'slug' => $this->slugFor($title),
            'description' => $this->paragraph(),
            'venue' => $this->faker->randomElement(['قاعة المؤسسة', 'الصحن الحيدري', 'مركز المؤتمرات']),
            'city' => $this->faker->city(),
            'starts_at' => $startsAt,
            ...$this->publishedAttributes(),
        ];
    }

    public function upcoming(): static
    {
        return $this->state(fn () => ['starts_at' => now()->addDays($this->faker->numberBetween(3, 90))]);
    }

    public function past(): static
    {
        return $this->state(fn () => ['starts_at' => now()->subDays($this->faker->numberBetween(3, 300))]);
    }
}
