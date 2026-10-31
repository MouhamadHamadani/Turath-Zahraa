<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ContactSettings extends Settings
{
    public ?string $address;

    public ?string $phone;

    public ?string $whatsapp;

    public ?string $email;

    public ?string $map_embed_url;

    public ?string $facebook_url;

    public ?string $instagram_url;

    public ?string $youtube_url;

    public ?string $telegram_url;

    public ?string $x_url;

    public static function group(): string
    {
        return 'contact';
    }

    /**
     * Social profiles that have a value, keyed by network, for the footer.
     *
     * @return array<string, array{url: string, label: string, icon: string}>
     */
    public function socialLinks(): array
    {
        $networks = [
            'facebook' => [$this->facebook_url, 'فيسبوك', 'fab fa-facebook-f'],
            'instagram' => [$this->instagram_url, 'إنستغرام', 'fab fa-instagram'],
            'youtube' => [$this->youtube_url, 'يوتيوب', 'fab fa-youtube'],
            'telegram' => [$this->telegram_url, 'تيليغرام', 'fab fa-telegram'],
            'x' => [$this->x_url, 'إكس', 'fab fa-x-twitter'],
        ];

        $links = [];

        foreach ($networks as $key => [$url, $label, $icon]) {
            if (filled($url)) {
                $links[$key] = ['url' => $url, 'label' => $label, 'icon' => $icon];
            }
        }

        return $links;
    }
}
