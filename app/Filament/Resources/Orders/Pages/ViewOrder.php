<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getFormSchema(): array
    {
        return [
            Section::make('Order Overview')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('order_number')
                                ->label('Order Number')
                                ->disabled()
                                ->extraAttributes(['class' => 'font-bold text-lg']),

                            TextInput::make('status')
                                ->disabled()
                                ->extraAttributes(['class' => 'font-semibold'])
                                ->color(fn ($state) => match ($state) {
                                    'pending' => 'warning',
                                    'processing' => 'primary',
                                    'shipped' => 'success',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary',
                                }),

                            TextInput::make('payment_status')
                                ->label('Payment')
                                ->disabled()
                                ->color(fn ($state) => match ($state) {
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'failed' => 'danger',
                                    'refunded' => 'secondary',
                                    default => 'gray',
                                }),
                        ]),
                    Grid::make(3)
                        ->schema([
                            TextInput::make('created_at')->label('Order Date')->disabled(),
                            TextInput::make('shipped_at')->label('Shipped Date')->disabled()->placeholder('Not shipped yet'),
                            TextInput::make('delivered_at')->label('Delivered Date')->disabled()->placeholder('Not delivered yet'),
                        ]),
                ]),

            Section::make('Tracking Information')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('shipping_company')
                                ->label('Carrier')
                                ->disabled()
                                ->placeholder('Not specified'),

                            TextInput::make('tracking_number')
                                ->label('Tracking Number')
                                ->disabled()
                                ->placeholder('Not available'),

                            TextInput::make('tracking_link')
                                ->label('Tracking URL')
                                ->disabled()
                                ->url(fn ($record) => $record->tracking_link)
                                ->openUrlInNewTab()
                                ->placeholder('Not available'),
                        ]),
                ])
                ->collapsible(),

            Section::make('Customer Information')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('full_name')->label('Customer Name')->disabled(),
                            TextInput::make('email')->disabled(),
                        ]),
                    TextInput::make('phone')->disabled(),
                ])
                ->collapsible(),

            Section::make('Shipping Address')
                ->schema([
                    TextInput::make('shipping_address')->label('Address')->disabled(),
                    Grid::make(4)
                        ->schema([
                            TextInput::make('city')->disabled(),
                            TextInput::make('state')->disabled(),
                            TextInput::make('zipcode')->disabled(),
                            TextInput::make('country')->disabled(),
                        ]),
                ])
                ->collapsible(),

            Section::make('Order Items')
                ->schema([
                    Repeater::make('items')
                        ->schema([
                            Grid::make(4)
                                ->schema([
                                    TextInput::make('product_name')->label('Product')->disabled(),
                                    TextInput::make('quantity')->label('Qty')->disabled(),
                                    TextInput::make('price')->label('Price')->disabled(),
                                    TextInput::make('subtotal')->label('Subtotal')->disabled(),
                                ]),
                        ]),
                ]),

            Section::make('Order Summary')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('subtotal')->disabled(),
                            TextInput::make('shipping_cost')->label('Shipping')->disabled(),
                        ]),
                    Grid::make(2)
                        ->schema([
                            TextInput::make('discount_amount')->label('Discount')->disabled(),
                            TextInput::make('total')->label('Total')->disabled(),
                        ]),
                    TextInput::make('shipping_method')->label('Shipping Method')->disabled(),
                ]),

            Section::make('Payment Information')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('payment_method')->disabled(),
                            TextInput::make('payment_intent_id')->disabled(),
                            TextInput::make('transaction_id')->disabled(),
                        ]),
                    TextInput::make('paid_at')->label('Payment Date')->disabled()->placeholder('Not paid yet'),
                ])
                ->collapsible()
                ->collapsed(),

            Section::make('Additional Information')
                ->schema([
                    Textarea::make('message')->label('Customer Message')->disabled(),
                    TextInput::make('coupon_code')->label('Coupon Code')->disabled(),
                ])
                ->collapsible()
                ->collapsed(),
        ];
    }
}
