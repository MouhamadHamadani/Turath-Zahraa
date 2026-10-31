<?php

namespace Database\Factories;

use App\Models\Document;

/**
 * @extends ArabicFactory<Document>
 */
class DocumentFactory extends ArabicFactory
{
    public function definition(): array
    {
        $title = $this->title();

        return [
            'title' => $title,
            'slug' => $this->slugFor($title),
            'description' => $this->paragraph(),
            'source' => $this->faker->randomElement(['مكتبة المؤسسة', 'أرشيف خاص', 'مكتبة عامة']),
            'date_label' => $this->faker->randomElement(['القرن الثالث عشر الهجري', '1350 هـ', 'تقريبًا 1920 م']),
            'year' => $this->faker->numberBetween(1850, 1990),
            'pages_count' => $this->faker->numberBetween(1, 40),
            'is_rare' => false,
            'is_downloadable' => false,
            'is_featured' => false,
            ...$this->publishedAttributes(),
        ];
    }

    public function rare(): static
    {
        return $this->state(fn () => ['is_rare' => true]);
    }
}
