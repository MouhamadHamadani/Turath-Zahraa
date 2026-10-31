<?php

namespace App\Filament\Resources\AudioTracks\Pages;

use App\Filament\Resources\AudioTracks\AudioTrackResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAudioTrack extends CreateRecord
{
    protected static string $resource = AudioTrackResource::class;
}
