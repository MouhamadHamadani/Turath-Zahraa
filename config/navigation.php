<?php

/**
 * Single source of truth for the public menu (desktop bar and mobile menu).
 *
 * Each entry is either a link ('route' + optional 'params') or a dropdown
 * ('children' with the same link shape). Rendered by App\Livewire\Navigation.
 */
return [
    ['label' => 'الرئيسية', 'route' => 'home'],
    ['label' => 'عن المؤسسة', 'route' => 'about-us'],
    ['label' => 'تراث السيدة الزهراء', 'route' => 'heritage'],
    [
        'label' => 'المكتبة',
        'children' => [
            ['label' => 'الكتب', 'route' => 'library.books'],
            ['label' => 'البحوث', 'route' => 'library.research'],
            ['label' => 'المطبوعات', 'route' => 'library.publications'],
            ['label' => 'الإصدارات', 'route' => 'library.publications', 'params' => ['type' => 'special']],
        ],
    ],
    [
        'label' => 'الأرشيف',
        'children' => [
            ['label' => 'الصوتيات', 'route' => 'archive.audio'],
            ['label' => 'الفيديوهات', 'route' => 'archive.videos'],
            ['label' => 'الصور', 'route' => 'archive.photos'],
            ['label' => 'الوثائق', 'route' => 'archive.documents'],
        ],
    ],
    ['label' => 'الأخبار والفعاليات', 'route' => 'news-events'],
    ['label' => 'تواصل معنا', 'route' => 'contact'],
];
