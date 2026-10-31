<?php

namespace App\Filament\Widgets;

use App\Models\AudioTrack;
use App\Models\Book;
use App\Models\ContactMessage;
use App\Models\Document;
use App\Models\Photo;
use App\Models\Video;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentStats extends StatsOverviewWidget
{
    protected static ?int $sort = -1;

    protected ?string $heading = 'المحتوى المنشور';

    protected function getStats(): array
    {
        $unread = ContactMessage::where('status', 'new')->count();

        return [
            Stat::make('الكتب', Book::published()->count()),
            Stat::make('الصوتيات', AudioTrack::published()->count()),
            Stat::make('الفيديوهات', Video::published()->count()),
            Stat::make('الصور', Photo::published()->count()),
            Stat::make('الوثائق', Document::published()->count()),
            Stat::make('رسائل غير مقروءة', $unread)->color($unread > 0 ? 'danger' : 'success'),
        ];
    }
}
