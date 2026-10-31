<?php

namespace Database\Factories;

use App\Models\Milestone;

/**
 * @extends ArabicFactory<Milestone>
 */
class MilestoneFactory extends ArabicFactory
{
    public function definition(): array
    {
        return [
            'year' => $this->faker->numberBetween(2018, 2026),
            'title' => $this->title(3),
            'description' => $this->sentence(),
            'sort_order' => 0,
        ];
    }
}
