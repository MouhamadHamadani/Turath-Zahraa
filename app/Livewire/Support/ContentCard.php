<?php

namespace App\Livewire\Support;

use App\Models\Article;
use App\Models\AudioTrack;
use App\Models\Book;
use App\Models\Document;
use App\Models\NewsArticle;
use App\Models\Photo;
use App\Models\Publication;
use App\Models\ResearchPaper;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;

/**
 * Turns any public content model into the array <x-content-card> renders,
 * so the section pages, the heritage page and the search page agree.
 * Eager load `media` and the person relation (author / researcher / speaker).
 */
class ContentCard
{
    /**
     * @return array{title: string, subtitle: ?string, image: ?string, badge: string|int|null, meta: array<int, string|int|null>, action: ?array{label: string, url: string}}
     */
    public static function make(Model $model): array
    {
        $card = match (true) {
            $model instanceof Book => [
                'subtitle' => $model->author?->name,
                'image' => self::media($model, 'cover'),
                'badge' => $model->published_year,
                'meta' => [$model->pages_count ? "{$model->pages_count} صفحة" : null],
                'action' => self::pdf($model),
            ],
            $model instanceof Publication => [
                'subtitle' => $model->author?->name,
                'image' => self::media($model, 'cover'),
                'badge' => $model->type->getLabel(),
                'meta' => [$model->issue_number ? "العدد {$model->issue_number}" : null, $model->published_year],
                'action' => self::pdf($model),
            ],
            $model instanceof ResearchPaper => [
                'subtitle' => $model->researcher?->name,
                'badge' => $model->published_year,
                'meta' => [$model->pages_count ? "{$model->pages_count} صفحة" : null],
                'action' => self::pdf($model),
            ],
            $model instanceof AudioTrack => [
                'subtitle' => $model->speaker?->name,
                'image' => self::media($model, 'thumbnail'),
                'badge' => $model->recorded_at?->format('Y'),
                'meta' => [$model->duration_seconds ? gmdate($model->duration_seconds >= 3600 ? 'G:i:s' : 'i:s', $model->duration_seconds) : null],
            ],
            $model instanceof Video => [
                'subtitle' => $model->speaker?->name,
                'image' => $model->thumbnail_url,
                'badge' => $model->recorded_at?->format('Y'),
                'action' => ['label' => 'مشاهدة على يوتيوب', 'url' => "https://www.youtube.com/watch?v={$model->youtube_id}"],
            ],
            $model instanceof Photo => [
                'title' => $model->title ?: ($model->caption ?: 'صورة'),
                'subtitle' => $model->title ? $model->caption : null,
                'image' => self::media($model, 'image', 'thumb'),
                'badge' => $model->year,
                'meta' => [$model->credit],
            ],
            $model instanceof Document => [
                'subtitle' => $model->source,
                'image' => self::media($model, 'pages', 'thumb'),
                'badge' => $model->is_rare ? 'نادرة' : null,
                'meta' => [$model->date_label ?: $model->year, $model->pages_count ? "{$model->pages_count} صفحة" : null],
            ],
            $model instanceof NewsArticle => [
                'subtitle' => $model->excerpt,
                'image' => self::media($model, 'cover'),
                'badge' => 'خبر',
                'meta' => [$model->published_at?->translatedFormat('j F Y')],
            ],
            $model instanceof Article => [
                'subtitle' => $model->excerpt,
                'image' => self::media($model, 'cover'),
                'meta' => [$model->reading_minutes ? "{$model->reading_minutes} دقائق قراءة" : null],
            ],
        };

        return $card + [
            'title' => $model->title,
            'subtitle' => null,
            'image' => null,
            'badge' => null,
            'meta' => [],
            'action' => null,
        ];
    }

    protected static function media(Model $model, string $collection, string $conversion = ''): ?string
    {
        return $model->getFirstMediaUrl($collection, $conversion) ?: null;
    }

    /** @return array{label: string, url: string}|null */
    protected static function pdf(Model $model): ?array
    {
        $url = $model->is_downloadable ? self::media($model, 'pdf') : null;

        return $url ? ['label' => 'تحميل PDF', 'url' => $url] : null;
    }
}
