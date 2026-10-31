<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProjectStatus: string implements HasLabel
{
    case Planned = 'planned';
    case Ongoing = 'ongoing';
    case Completed = 'completed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Planned => 'مخطّط',
            self::Ongoing => 'جارٍ',
            self::Completed => 'مكتمل',
        };
    }
}
