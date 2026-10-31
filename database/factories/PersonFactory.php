<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Support\Str;

/**
 * @extends ArabicFactory<Person>
 */
class PersonFactory extends ArabicFactory
{
    public function definition(): array
    {
        $name = $this->faker->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name, language: null).'-'.Str::lower(Str::random(6)),
            'honorific' => $this->faker->randomElement(['الشيخ', 'السيد', 'الدكتور', 'الأستاذ']),
            'specialty' => $this->faker->randomElement(['التاريخ الإسلامي', 'الحديث', 'السيرة', 'الأدب']),
            'bio' => $this->paragraph(),
            'is_listed' => true,
            'is_featured' => false,
            'sort_order' => 0,
        ];
    }

    public function unlisted(): static
    {
        return $this->state(fn () => ['is_listed' => false]);
    }
}
