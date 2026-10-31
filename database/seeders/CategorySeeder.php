<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Slugs are fixed ASCII keys so URLs stay stable even if a name is edited.
     *
     * @return array<string, array<string, string>>
     */
    protected function lists(): array
    {
        return [
            CategoryType::HeritageTopic->value => [
                'sira' => 'السيرة',
                'narrations' => 'الروايات',
                'sermons' => 'الخطب',
                'sayings' => 'الكلمات',
                'positions' => 'المواقف',
                'historical-events' => 'الأحداث التاريخية',
                'studies' => 'الدراسات',
                'research' => 'البحوث',
                'books' => 'الكتب',
                'lectures' => 'المحاضرات',
            ],
            CategoryType::Audio->value => [
                'lectures' => 'المحاضرات',
                'sermons' => 'الخطب',
                'majalis' => 'المجالس',
                'seminars' => 'الندوات',
                'meetings' => 'اللقاءات',
                'historical-recordings' => 'التسجيلات التاريخية',
            ],
            CategoryType::Video->value => [
                'lectures' => 'المحاضرات',
                'programs' => 'البرامج',
                'seminars' => 'الندوات',
                'interviews' => 'المقابلات',
                'events' => 'الفعاليات',
                'documentaries' => 'الأفلام الوثائقية',
                'historical-material' => 'المواد التاريخية',
                'media-coverage' => 'التغطيات الإعلامية',
            ],
            CategoryType::Photo->value => [
                'event-photos' => 'صور الفعاليات',
                'people-photos' => 'صور الشخصيات',
                'publication-photos' => 'صور الإصدارات',
                'historical-photos' => 'صور تاريخية',
                'foundation-photos' => 'صور المؤسسة',
            ],
            CategoryType::Document->value => [
                'manuscripts' => 'المخطوطات',
                'historical-documents' => 'الوثائق التاريخية',
                'old-pages' => 'الصفحات القديمة',
                'correspondence' => 'المخاطبات',
                'rare-documents' => 'الوثائق النادرة',
            ],
            CategoryType::News->value => [
                'foundation-news' => 'أخبار المؤسسة',
                'new-releases' => 'الإصدارات الجديدة',
                'activities' => 'النشاطات',
                'visits' => 'الزيارات',
                'participations' => 'المشاركات',
            ],
        ];
    }

    public function run(): void
    {
        foreach ($this->lists() as $type => $items) {
            $order = 0;

            foreach ($items as $slug => $name) {
                Category::updateOrCreate(
                    ['type' => $type, 'slug' => $slug],
                    ['name' => $name, 'sort_order' => ++$order, 'is_active' => true],
                );
            }
        }
    }
}
