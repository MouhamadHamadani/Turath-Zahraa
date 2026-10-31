<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public string $tagline;

    public string $hero_title;

    public string $hero_subtitle;

    public string $about_summary;

    public string $headquarters;

    /** Each goal is a ['title' => ..., 'description' => ...] pair (array shapes are not parsed by Spatie Settings). */
    public array $goals;

    public static function group(): string
    {
        return 'general';
    }
}
