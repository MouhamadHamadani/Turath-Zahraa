<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PublicationType: string implements HasLabel
{
    case Magazine = 'magazine';
    case Booklet = 'booklet';
    case Newsletter = 'newsletter';
    case Special = 'special';

    public function getLabel(): string
    {
        return match ($this) {
            self::Magazine => 'مجلة',
            self::Booklet => 'كتيّب',
            self::Newsletter => 'نشرة',
            self::Special => 'إصدار خاص',
        };
    }
}
