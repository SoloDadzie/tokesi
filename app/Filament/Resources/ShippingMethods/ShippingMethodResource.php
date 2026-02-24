<?php

namespace App\Filament\Resources\ShippingMethods;


use App\Filament\Resources\ShippingMethods\Pages;
use App\Models\ShippingMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class ShippingMethodResource extends Resource
{
    protected static ?string $model = ShippingMethod::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static string|\UnitEnum|null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Shipping Method Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Standard Delivery'),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('Brief description of this shipping method'),
                        
                        Forms\Components\TextInput::make('delivery_time')
                            ->maxLength(255)
                            ->placeholder('e.g., 2-3 Days Delivery')
                            ->helperText('Estimated delivery timeframe'),
                        
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->prefix('£')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                    ])->columns(2),

                Section::make('Location Restrictions')
                    ->description('Leave empty to apply to all locations')
                    ->schema([
                        Forms\Components\TextInput::make('country')
                            ->maxLength(100)
                            ->placeholder('e.g., United Kingdom, Nigeria')
                            ->helperText('Leave empty for all countries'),
                        
                        Forms\Components\TextInput::make('state')
                            ->maxLength(100)
                            ->placeholder('e.g., Lagos, California')
                            ->helperText('Leave empty for all states within the country'),
                    ])->columns(2)
                    ->collapsible(),

                Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Only active shipping methods are available at checkout')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('price')
                    ->money('GBP')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('delivery_time')
                    ->label('Delivery Time')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('country')
                    ->default('All Countries')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('state')
                    ->default('All States')
                    ->badge()
                    ->color('warning'),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All methods')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                
                Tables\Filters\SelectFilter::make('country')
                    ->options(function () {
                        return ShippingMethod::whereNotNull('country')
                            ->pluck('country', 'country')
                            ->unique();
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    
                    BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListShippingMethods::route('/'),
            'create' => Pages\CreateShippingMethod::route('/create'),
            'edit' => Pages\EditShippingMethod::route('/{record}/edit'),
        ];
    }
}