<?php

namespace Database\Seeders;

use App\Models\Milestone;
use Illuminate\Database\Seeder;

class MilestoneSeeder extends Seeder
{
    /**
     * Created only when missing, so edits made in the admin panel survive a re-seed.
     */
    public function run(): void
    {
        $milestones = [
            [
                'year' => 2020,
                'title' => 'تأسيس المؤسسة',
                'description' => 'تأسست مؤسسة إحياء تراث الصديقة الشهيدة بهدف حفظ التراث الثقافي والتاريخي وتوثيقه.',
                'image' => 'foundation.jpg',
            ],
            [
                'year' => 2021,
                'title' => 'بدء المبادرات التعليمية',
                'description' => 'أطلقت المؤسسة ورش العمل والبرامج التعليمية لتعزيز الوعي بالتراث بين الأجيال الجديدة.',
                'image' => 'education.jpg',
            ],
            [
                'year' => 2022,
                'title' => 'توسيع التوثيق والأرشفة',
                'description' => 'بدأت المؤسسة بجمع المواد التراثية وتوثيقها وأرشفتها لإتاحتها للباحثين والمهتمين.',
                'image' => 'archiving.jpg',
            ],
        ];

        foreach ($milestones as $order => ['image' => $image, 'year' => $year, 'title' => $title, 'description' => $description]) {
            $milestone = Milestone::firstOrCreate(
                ['year' => $year, 'title' => $title],
                ['description' => $description, 'sort_order' => $order + 1],
            );

            $file = public_path("images/about-us/timeline/{$image}");

            if ($milestone->wasRecentlyCreated && is_file($file)) {
                $milestone->addMedia($file)->preservingOriginal()->toMediaCollection('image');
            }
        }
    }
}
