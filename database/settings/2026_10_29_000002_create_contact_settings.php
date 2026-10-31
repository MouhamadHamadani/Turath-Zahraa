<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('contact.address', 'العراق، النجف الأشرف');

        foreach (['phone', 'whatsapp', 'email', 'map_embed_url', 'facebook_url', 'instagram_url', 'youtube_url', 'telegram_url', 'x_url'] as $name) {
            $this->migrator->add("contact.{$name}", null);
        }
    }
};
