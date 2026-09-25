<?php

namespace App\Livewire;

use Livewire\Component;

class AboutUs extends Component
{
    public array $timeline = [
        [
            'year' => '2020',
            'title' => 'تأسيس المؤسسة',
            'description' => 'تأسست مؤسسة إحياء تراث الصديقة الشهيدة بهدف حفظ التراث الثقافي والتاريخي وتوثيقه.',
            'image' => 'foundation.jpg',
        ],
        [
            'year' => '2021',
            'title' => 'بدء المبادرات التعليمية',
            'description' => 'أطلقت المؤسسة ورش العمل والبرامج التعليمية لتعزيز الوعي بالتراث بين الأجيال الجديدة.',
            'image' => 'education.jpg',
        ],
        [
            'year' => '2022',
            'title' => 'توسيع التوثيق والأرشفة',
            'description' => 'بدأت المؤسسة بجمع المواد التراثية وتوثيقها وأرشفتها لإتاحتها للباحثين والمهتمين.',
            'image' => 'archiving.jpg',
        ],
    ];

    public function render()
    {
        return view('livewire.about-us');
    }
}
