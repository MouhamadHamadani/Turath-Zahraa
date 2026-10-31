<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Project;

/**
 * @extends ArabicFactory<Project>
 */
class ProjectFactory extends ArabicFactory
{
    public function definition(): array
    {
        $name = $this->title(3);

        return [
            'name' => $name,
            'slug' => $this->slugFor($name),
            'goal' => $this->sentence(),
            'description' => '<p>'.$this->paragraph().'</p>',
            'beneficiaries' => 'الباحثون وطلاب الحوزات والجامعات',
            'status' => ProjectStatus::Ongoing,
            'started_at' => $this->faker->dateTimeBetween('-4 years', '-1 month'),
            'is_featured' => false,
            'sort_order' => 0,
        ];
    }
}
