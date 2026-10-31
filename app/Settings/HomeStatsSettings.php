<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Manual overrides for the homepage numbers. A null value means
 * "count it from the database".
 */
class HomeStatsSettings extends Settings
{
    public ?int $books_count;

    public ?int $audio_count;

    public ?int $videos_count;

    public ?int $archive_count;

    public ?int $events_count;

    public ?int $followers_count;

    public static function group(): string
    {
        return 'home_stats';
    }
}
