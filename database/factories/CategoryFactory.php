<?php

namespace Database\Factories;

use App\Enums\CategoryType;
use App\Models\Category;

/**
 * @extends ArabicFactory<Category>
 */
class CategoryFactory extends ArabicFactory
{
    public function definition(): array
    {
        $name = $this->title(3);

        return [
            'type' => CategoryType::HeritageTopic,
            'name' => $name,
            'slug' => $this->slugFor($name),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }

    public function ofType(CategoryType $type): static
    {
        return $this->state(fn () => ['type' => $type]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
