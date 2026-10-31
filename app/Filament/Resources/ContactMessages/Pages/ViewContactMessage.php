<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('markRead')
                ->label('تعليم كمقروءة')
                ->icon('heroicon-o-envelope-open')
                ->visible(fn () => $this->record->status === 'new')
                ->action(fn () => $this->record->update(['status' => 'read', 'read_at' => now()])),
            Action::make('markReplied')
                ->label('تم الرد')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => in_array($this->record->status, ['new', 'read'], true))
                ->action(fn () => $this->record->update([
                    'status' => 'replied',
                    'read_at' => $this->record->read_at ?? now(),
                    'replied_at' => now(),
                ])),
            Action::make('markSpam')
                ->label('رسالة مزعجة')
                ->icon('heroicon-o-no-symbol')
                ->color('gray')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status !== 'spam')
                ->action(fn () => $this->record->update(['status' => 'spam'])),
        ];
    }
}
