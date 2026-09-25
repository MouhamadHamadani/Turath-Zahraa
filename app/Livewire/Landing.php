<?php

namespace App\Livewire;

use Livewire\Component;

class Landing extends Component
{
    public array $widgets = [];
    public array $events = [];

    public function mount(): void
    {
        $this->widgets = [
            [
                'title' => 'الكتب والإصدارات',
                'description' => 'اكتشف مجموعة واسعة من الكتب والإصدارات...',
                'url' => '/books',
                'image' => 'books.jpg',
                'icon' => 'fa-solid fa-book',
                'value' => 1200,
            ],
            [
                'title' => 'الصوتيات',
                'description' => 'استمع إلى مجموعة من التسجيلات الصوتية...',
                'url' => '/audio',
                'image' => 'audio.jpg',
                'icon' => 'fa-solid fa-headphones',
                'value' => 800,
            ],
            [
                'title' => 'الفيديوهات',
                'description' => 'شاهد مجموعة من الفيديوهات...',
                'url' => '/videos',
                'image' => 'videos.jpg',
                'icon' => 'fa-solid fa-video',
                'value' => 500,
            ],
            [
                'title' => 'الصور والوثائق',
                'description' => 'استعرض مجموعة من الصور والوثائق التاريخية...',
                'url' => '/images',
                'image' => 'images.jpg',
                'icon' => 'fa-regular fa-images',
                'value' => 1500,
            ],
            [
                'title' => 'البرامج والفعاليات',
                'description' => 'تعرف على البرامج والفعاليات...',
                'url' => '/events',
                'image' => 'events.jpg',
                'icon' => 'fa-solid fa-calendar-days',
                'value' => 300,
            ],
        ];

        $this->events = [
            [
                'title' => 'ورشة عمل حول التراث',
                'slug' => 'heritage-workshop',
                'date' => '2026-11-15',
                'description' => 'انضم إلينا في ورشة عمل حول التراث الثقافي...',
                'image' => 'workshop.jpg',
                'status' => 'upcoming',
            ],
            [
                'title' => 'ندوة تاريخية',
                'slug' => 'historical-seminar',
                'date' => '2024-08-10',
                'description' => 'شارك في ندوة تاريخية حول التراث...',
                'image' => 'seminar.jpg',
                'status' => 'past',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.landing');
    }
}
