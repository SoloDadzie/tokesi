<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContact extends ViewRecord
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mark_as_read')
                ->label('Mark as Read')
                ->icon('heroicon-o-envelope-open')
                ->color('info')
                ->visible(fn () => $this->record->status === 'unread')
                ->action(fn () => $this->record->update(['status' => 'read'])),

            Actions\Action::make('mark_as_replied')
                ->label('Mark as Replied')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status !== 'replied')
                ->action(fn () => $this->record->update(['status' => 'replied'])),

            Actions\Action::make('reply')
                ->label('Reply via Email')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->url(fn () => 'mailto:' . $this->record->email . '?subject=Re: ' . urlencode($this->record->subject))
                ->openUrlInNewTab(),

            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Automatically mark as read when viewing
        if ($this->record->status === 'unread') {
            $this->record->update(['status' => 'read']);
        }

        return $data;
    }
}