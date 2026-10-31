<?php

namespace App\Filament\Resources\AudioTracks\Pages;

use App\Filament\Resources\AudioTracks\AudioTrackResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAudioTracks extends ListRecords
{
    protected static string $resource = AudioTrackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
