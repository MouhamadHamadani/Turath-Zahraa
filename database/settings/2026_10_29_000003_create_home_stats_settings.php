<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        foreach (['books_count', 'audio_count', 'videos_count', 'archive_count', 'events_count'] as $name) {
            $this->migrator->add("home_stats.{$name}", null);
        }

        // Social followers cannot be counted from the database; this is the figure the site already claimed.
        $this->migrator->add('home_stats.followers_count', 15000);
    }
};
