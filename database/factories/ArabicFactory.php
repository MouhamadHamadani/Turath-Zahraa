<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Base for the content factories: Arabic (ar_SA) names and cities, text made
 * from a small heritage vocabulary, and the published / draft / scheduled
 * states shared by every Publishable model.
 *
 * Text comes from our own word list instead of Faker's realText(), which
 * rebuilds a huge Arabic Markov table on every call and exhausts memory.
 *
 * @template TModel of Model
 *
 * @extends Factory<TModel>
 */
abstract class ArabicFactory extends Factory
{
    protected const WORDS = [
        'الصديقة', 'الزهراء', 'فاطمة', 'سيرة', 'خطبة', 'مواقف', 'تراث', 'رواية', 'حديث', 'بحث', 'دراسة', 'تاريخ',
        'مجلس', 'محاضرة', 'وثيقة', 'مخطوط', 'كتاب', 'إحياء', 'فكر', 'أثر', 'مناقب', 'فضائل', 'علم', 'أدب', 'منبر',
        'شعر', 'عصمة', 'عترة', 'بيت', 'النبوة', 'الإسلام', 'المرأة', 'القدوة', 'الصبر', 'الوفاء', 'الحوزة', 'النجف',
    ];

    protected static ?Generator $arabicFaker = null;

    protected function withFaker(): Generator
    {
        return self::$arabicFaker ??= FakerFactory::create('ar_SA');
    }

    /** A few random words, no trailing punctuation. */
    protected function title(int $words = 4): string
    {
        return implode(' ', $this->faker->randomElements(self::WORDS, $words));
    }

    protected function sentence(int $words = 12): string
    {
        return $this->title($words).'.';
    }

    protected function paragraph(int $sentences = 4): string
    {
        return implode(' ', array_map(fn () => $this->sentence($this->faker->numberBetween(8, 14)), range(1, $sentences)));
    }

    /** Arabic-preserving slug, made unique with a random suffix. */
    protected function slugFor(string $text): string
    {
        return Str::slug($text, language: null).'-'.Str::lower(Str::random(6));
    }

    /**
     * Default `status` + `published_at` for a visible item.
     *
     * @return array{status: ContentStatus, published_at: Carbon}
     */
    protected function publishedAttributes(): array
    {
        return [
            'status' => ContentStatus::Published,
            'published_at' => now()->subDays($this->faker->numberBetween(1, 300)),
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => $this->publishedAttributes());
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => ContentStatus::Draft, 'published_at' => null]);
    }

    /** Marked published, but the publish date is still in the future. */
    public function scheduled(): static
    {
        return $this->state(fn () => [
            'status' => ContentStatus::Published,
            'published_at' => now()->addDays($this->faker->numberBetween(1, 30)),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
