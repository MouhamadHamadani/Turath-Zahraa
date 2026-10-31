<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CategoryType: string implements HasLabel
{
    case HeritageTopic = 'heritage_topic'; // السيرة، الروايات، الخطب ... (10 تصنيفات صفحة التراث)
    case Book = 'book';
    case Publication = 'publication';
    case Research = 'research';
    case Audio = 'audio';
    case Video = 'video';
    case Photo = 'photo';
    case Document = 'document';
    case News = 'news';

    public function getLabel(): string
    {
        return match ($this) {
            self::HeritageTopic => 'مواضيع التراث',
            self::Book => 'الكتب',
            self::Publication => 'المطبوعات',
            self::Research => 'البحوث',
            self::Audio => 'الصوتيات',
            self::Video => 'الفيديوهات',
            self::Photo => 'الصور',
            self::Document => 'الوثائق',
            self::News => 'الأخبار',
        };
    }
}
