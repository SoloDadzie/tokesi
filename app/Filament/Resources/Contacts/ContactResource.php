<?php

namespace App\Filament\Resources\Contacts;

use App\Filament\Resources\Contacts\Pages;
use App\Models\Contact;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->disabled(),

                        TextInput::make('subject')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->columnSpanFull(),

                        Textarea::make('message')
                            ->required()
                            ->rows(6)
                            ->disabled()
                            ->columnSpanFull(),

                        Select::make('status')
                            ->options([
                                'unread' => 'Unread',
                                'read' => 'Read',
                                'replied' => 'Replied',
                            ])
                            ->required()
                            ->default('unread'),
                    ])->columns(2),

                Section::make('Additional Details')
                    ->schema([
                        TextInput::make('ip_address')
                            ->label('IP Address')
                            ->disabled(),

                        TextInput::make('created_at')
                            ->label('Submitted At')
                            ->disabled(),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('status')
                    ->icon(fn (string $state): string => match ($state) {
                        'unread' => 'heroicon-o-envelope',
                        'read' => 'heroicon-o-envelope-open',
                        'replied' => 'heroicon-o-check-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'unread' => 'warning',
                        'read' => 'info',
                        'replied' => 'success',
                    })
                    ->sortable()
                    ->width(60)
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Contact $record): string => $record->email),

                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('message')
                    ->searchable()
                    ->limit(60)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'unread',
                        'info' => 'read',
                        'success' => 'replied',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->description(fn (Contact $record): string => $record->created_at->format('M j, Y g:i A')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'unread' => 'Unread',
                        'read' => 'Read',
                        'replied' => 'Replied',
                    ])
                    ->multiple(),
            ])
            ->recordActions([
                Action::make('mark_as_read')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-envelope-open')
                    ->color('info')
                    ->visible(fn (Contact $record) => $record->status === 'unread')
                    ->action(fn (Contact $record) => $record->update(['status' => 'read'])),

                Action::make('mark_as_replied')
                    ->label('Mark as Replied')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Contact $record) => $record->status !== 'replied')
                    ->action(fn (Contact $record) => $record->update(['status' => 'replied'])),

                Action::make('reply')
                    ->label('Reply via Email')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->url(fn (Contact $record) => 'mailto:' . $record->email . '?subject=Re: ' . urlencode($record->subject))
                    ->openUrlInNewTab(),

                ViewAction::make(),

                Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->delete())
                    ->color('danger')
                    ->icon('heroicon-o-trash'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('mark_as_read')
                        ->label('Mark as Read')
                        ->icon('heroicon-o-envelope-open')
                        ->color('info')
                        ->accessSelectedRecords()
                        ->action(fn ($records) => $records->each->update(['status' => 'read']))
                        ->deselectRecordsAfterCompletion(),

                    Action::make('mark_as_replied')
                        ->label('Mark as Replied')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->accessSelectedRecords()
                        ->action(fn ($records) => $records->each->update(['status' => 'replied']))
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'view' => Pages\ViewContact::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'unread')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}