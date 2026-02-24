<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static string|\UnitEnum|null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Order Number')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\Select::make('status')
                            ->label('Order Status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                // Auto-set tracking required fields visibility
                                if ($state === 'shipped' && !$get('shipped_at')) {
                                    $set('shipped_at', now());
                                }
                                if ($state === 'delivered' && !$get('delivered_at')) {
                                    $set('delivered_at', now());
                                }
                            })
                            ->helperText('⚠️ Changing to "Shipped" or "Delivered" will automatically send email notifications'),
                        
                        Forms\Components\Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required(),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\DateTimePicker::make('paid_at')
                                    ->label('Paid At'),
                                Forms\Components\DateTimePicker::make('shipped_at')
                                    ->label('Shipped At'),
                                Forms\Components\DateTimePicker::make('delivered_at')
                                    ->label('Delivered At'),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Tracking Information')
                    ->description('Add tracking details to inform customers about their shipment')
                    ->schema([
                        Forms\Components\TextInput::make('shipping_company')
                            ->label('Shipping Company / Carrier')
                            ->placeholder('e.g., FedEx, UPS, DHL, Royal Mail')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Tracking Number')
                            ->placeholder('Enter tracking/consignment number')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('tracking_link')
                            ->label('Tracking URL (Optional)')
                            ->url()
                            ->placeholder('https://www.carrier.com/track?number=...')
                            ->helperText('Direct link to track the package')
                            ->maxLength(500),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Customer Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Shipping Address')
                    ->schema([
                        Forms\Components\Textarea::make('shipping_address')
                            ->required()
                            ->rows(2)
                            ->maxLength(500),
                        
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('state')
                                    ->required()
                                    ->maxLength(100),
                            ]),
                        
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('zipcode')
                                    ->required()
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('country')
                                    ->required()
                                    ->maxLength(100),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Order Details')
                    ->schema([
                        Forms\Components\TextInput::make('shipping_method')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('subtotal')
                                    ->numeric()
                                    ->prefix('£')
                                    ->disabled()
                                    ->dehydrated(false),
                                Forms\Components\TextInput::make('shipping_cost')
                                    ->numeric()
                                    ->prefix('£')
                                    ->disabled()
                                    ->dehydrated(false),
                                Forms\Components\TextInput::make('discount_amount')
                                    ->numeric()
                                    ->prefix('£')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                        
                        Forms\Components\TextInput::make('total')
                            ->numeric()
                            ->prefix('£')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\Textarea::make('message')
                            ->label('Customer Message')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->collapsible(),

                Section::make('Payment Information')
                    ->schema([
                        Forms\Components\TextInput::make('payment_method')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('payment_intent_id')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('transaction_id')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Order number copied'),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Customer')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'primary' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ]),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'secondary' => 'refunded',
                    ]),

                Tables\Columns\TextColumn::make('total')
                    ->money('gbp')
                    ->sortable(),

                Tables\Columns\IconColumn::make('tracking_number')
                    ->label('Tracked')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->getStateUsing(fn ($record) => !empty($record->tracking_number)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('M j, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipped_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('delivered_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),

                Tables\Filters\Filter::make('has_tracking')
                    ->label('Has Tracking')
                    ->query(fn ($query) => $query->whereNotNull('tracking_number')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'processing')->count();
    }
}