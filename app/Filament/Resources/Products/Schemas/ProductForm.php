<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function schema(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Basic Information')
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, $state, callable $set) {
                            if ($operation === 'create') {
                                $set('slug', Str::slug($state));
                            }
                        })
                        ->maxLength(255),
                    
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    
                    TextInput::make('sku')
                        ->label('SKU')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                ])->columns(3),

            Section::make('Pricing')
                ->schema([
                    TextInput::make('price')
                        ->required()
                        ->prefix('£')
                        ->numeric()
                        ->minValue(0)
                        ->step(0.01),
                    
                    TextInput::make('compare_at_price')
                        ->label('Compare at Price')
                        ->prefix('£')
                        ->numeric()
                        ->minValue(0)
                        ->step(0.01)
                        ->helperText('Original price for showing discounts'),
                ])->columns(2),

            Section::make('Product Type & Inventory')
                ->schema([
                    Select::make('type')
                        ->options([
                            'physical' => 'Physical Book',
                            'pdf' => 'PDF Only',
                            'both' => 'Physical + PDF',
                        ])
                        ->required()
                        ->default('physical')
                        ->live(),
                    
                    TextInput::make('inventory_qty')
                        ->label('Inventory Quantity')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->visible(fn (callable $get): bool => in_array($get('type'), ['physical', 'both'])),
                    
                    FileUpload::make('pdf_file_path')
                        ->label('PDF File')
                        ->directory('products/pdfs')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(51200)
                        ->visible(fn (callable $get): bool => in_array($get('type'), ['pdf', 'both'])),
                ])->columns(3),

            Section::make('Descriptions')
                ->schema([
                    Textarea::make('short_description')
                        ->label('Short Description')
                        ->rows(3)
                        ->maxLength(500),
                    
                    RichEditor::make('long_description')
                        ->label('Long Description')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'bulletList',
                            'orderedList',
                            'h2',
                            'h3',
                        ])
                        ->columnSpanFull(),
                ]),

            Section::make('Images')
                ->schema([
                    FileUpload::make('images')
                        ->label('Product Images')
                        ->multiple()
                        ->directory('products/images')
                        ->disk('public')
                        ->image()
                        ->reorderable()
                        ->maxFiles(10)
                        ->helperText('First image will be set as primary. You can drag to reorder.')
                        ->columnSpanFull(),
                ]),

            Section::make('Categories & Tags')
                ->schema([
                    Select::make('categories')
                        ->relationship('categories', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->required(),
                            Textarea::make('description')
                                ->rows(3),
                        ]),
                    
                    Select::make('tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->required(),
                        ]),
                ])->columns(2),

            Section::make('Product Coupon')
                ->description('Apply a special discount to this product only')
                ->schema([
                    TextInput::make('coupon_code')
                        ->label('Coupon Code')
                        ->maxLength(50)
                        ->helperText('Optional: Unique code customers can use for this product')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('coupon_code', $state ? strtoupper($state) : null)),
                    
                    TextInput::make('coupon_percentage')
                        ->label('Discount Percentage')
                        ->suffix('%')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.01),
                    
                    TextInput::make('coupon_usage_limit')
                        ->label('Usage Limit')
                        ->numeric()
                        ->minValue(1)
                        ->helperText('Leave empty for unlimited usage'),
                    
                    DateTimePicker::make('coupon_valid_from')
                        ->label('Valid From')
                        ->native(false),
                    
                    DateTimePicker::make('coupon_valid_until')
                        ->label('Valid Until')
                        ->native(false)
                        ->after('coupon_valid_from'),
                ])->columns(3)
                ->collapsible(),

            Section::make('Status')
                ->schema([
                    Toggle::make('is_featured')
                        ->label('Featured')
                        ->helperText('Show on homepage featured section'),
                    
                    Toggle::make('is_new_arrival')
                        ->label('New Arrival')
                        ->helperText('Mark as new product'),
                    
                    Toggle::make('is_active')
                        ->label('Active')
                        ->helperText('Product visible on website')
                        ->default(true),
                ])->columns(3),
        ]);
    }
}