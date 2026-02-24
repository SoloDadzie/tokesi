<?php

namespace App\Filament\Resources\Reviews;


use App\Filament\Resources\Reviews\Pages;
use App\Models\Review;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static string|\UnitEnum|null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Review Details')
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('last_name')
                            ->maxLength(255),

                        Select::make('rating')
                            ->options([
                                '5' => '5 Stars - Excellent',
                                '4.5' => '4.5 Stars - Very Good',
                                '4' => '4 Stars - Good',
                                '3.5' => '3.5 Stars - Above Average',
                                '3' => '3 Stars - Average',
                                '2.5' => '2.5 Stars - Below Average',
                                '2' => '2 Stars - Poor',
                                '1.5' => '1.5 Stars - Very Poor',
                                '1' => '1 Star - Terrible',
                            ])
                            ->required(),

                        Textarea::make('review_text')
                            ->label('Review')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000),

                        Toggle::make('is_approved')
                            ->label('Approved')
                            ->helperText('Only approved reviews are visible on the website')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.title')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Reviewer')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('rating')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 4.5 => 'success',
                        $state >= 3.5 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => $state . ' ⭐')
                    ->sortable(),

                Tables\Columns\TextColumn::make('review_text')
                    ->label('Review')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('product')
                    ->relationship('product', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Approval Status')
                    ->placeholder('All reviews')
                    ->trueLabel('Approved only')
                    ->falseLabel('Pending approval'),

                Tables\Filters\Filter::make('rating')
                    ->form([
                        Select::make('min_rating')
                            ->label('Minimum Rating')
                            ->options([
                                '1' => '1 Star',
                                '2' => '2 Stars',
                                '3' => '3 Stars',
                                '4' => '4 Stars',
                                '5' => '5 Stars',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        if (isset($data['min_rating'])) {
                            $query->where('rating', '>=', $data['min_rating']);
                        }
                    }),
            ])
            ->recordActions([
                Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Review $record) => $record->update(['is_approved' => true]))
                    ->visible(fn (Review $record) => !$record->is_approved)
                    ->requiresConfirmation(),

                Action::make('unapprove')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (Review $record) => $record->update(['is_approved' => false]))
                    ->visible(fn (Review $record) => $record->is_approved)
                    ->requiresConfirmation(),

                EditAction::make(),
                
                Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->delete())
                    ->color('danger')
                    ->icon('heroicon-o-trash'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->accessSelectedRecords()
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_approved' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Action::make('unapprove')
                        ->label('Unapprove Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->accessSelectedRecords()
                        ->action(fn ($records) => $records->each->update(['is_approved' => false]))
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
            'view' => Pages\ViewReview::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_approved', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}