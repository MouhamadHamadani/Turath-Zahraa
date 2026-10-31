<?php

namespace App\Filament\Resources\AudioTracks\Pages;

use App\Filament\Resources\AudioTracks\AudioTrackResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditAudioTrack extends EditRecord
{
    protected static string $resource = AudioTrackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
