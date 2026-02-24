<?php

namespace App\Filament\Resources\Coupons;

use App\Filament\Resources\Coupons\Pages;
use App\Models\Coupon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static string|\UnitEnum|null $navigationGroup = 'Shop';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Coupon Details')
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->hint('Will be converted to uppercase')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('code', Str::upper($state))),
                        
                        TextInput::make('percentage')
                            ->required()
                            ->suffix('%')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01),
                        
                        TextInput::make('usage_limit')
                            ->label('Usage Limit')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Leave empty for unlimited usage'),
                    ])->columns(3),

                Section::make('Validity Period')
                    ->schema([
                        DateTimePicker::make('valid_from')
                            ->label('Valid From')
                            ->native(false),
                        
                        DateTimePicker::make('valid_until')
                            ->label('Valid Until')
                            ->native(false)
                            ->after('valid_from'),
                    ])->columns(2),

                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Coupon code copied')
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('percentage')
                    ->suffix('%')
                    ->sortable()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Used')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('usage_limit')
                    ->label('Limit')
                    ->default('Unlimited')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('valid_from')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('valid_until')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All coupons')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                
                Tables\Filters\Filter::make('valid')
                    ->query(fn ($query) => $query->where('is_active', true)
                        ->where(function ($q) {
                            $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
                        })
                        ->where(function ($q) {
                            $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                        }))
                    ->label('Currently Valid'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->delete())
                    ->color('danger')
                    ->icon('heroicon-o-trash'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    
                    Action::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    
                    Action::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}