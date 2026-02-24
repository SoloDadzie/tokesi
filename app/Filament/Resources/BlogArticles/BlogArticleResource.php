<?php

namespace App\Filament\Resources\BlogArticles;

use App\Filament\Resources\BlogArticles\Pages;
use App\Models\BlogArticle;
use App\Models\Media;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Illuminate\Support\Str;

class BlogArticleResource extends Resource
{
    protected static ?string $model = BlogArticle::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Articles';
    protected static ?string $modelLabel = 'Article';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Article Details')
                ->schema([
                    Forms\Components\Select::make('type')
                        ->options([
                            'blog' => 'Blog',
                            'event' => 'Event',
                        ])
                        ->required()
                        ->default('blog'),

                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, $set, $get) {
                            if (!$get('slug')) {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Auto-generated from title, but you can customize it'),

                    Forms\Components\TextInput::make('author_name')
                        ->required()
                        ->default('Tokesi Akinola')
                        ->maxLength(255),

                    // Featured Image with Media Library Picker - FIXED
                    Forms\Components\FileUpload::make('featured_image')
                        ->label('Featured Image')
                        ->image()
                        ->disk('public')
                        ->directory('blog-images')
                        ->imageEditor()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth('1200')
                        ->imageResizeTargetHeight('675')
                        ->maxSize(5120)
                        ->helperText('Upload new image or click button below to select from media library')
                        ->hintAction(
                            Action::make('selectFromLibrary')
                                ->label('Browse Library')
                                ->icon('heroicon-o-photo')
                                ->color('info')
                                ->modalHeading('Select Image from Media Library')
                                ->modalDescription('Click on an image to select it')
                                ->modalWidth('6xl')
                                ->modalSubmitActionLabel('Select Image')
                                ->form([
                                    Forms\Components\Radio::make('selected_image')
                                        ->label('')
                                        ->required()
                                        ->options(function () {
                                            return Media::query()
                                                ->where('type', 'image')
                                                ->whereIn('collection', ['blog', 'general'])
                                                ->latest()
                                                ->limit(50)
                                                ->get()
                                                ->mapWithKeys(function ($media) {
                                                    // Store the path without 'storage/' prefix
                                                    $cleanPath = str_replace('storage/', '', $media->path);
                                                    $cleanPath = ltrim($cleanPath, '/');
                                                    
                                                    return [
                                                        $cleanPath => new \Illuminate\Support\HtmlString(
                                                            '<div class="group relative">
                                                                <div class="aspect-video overflow-hidden rounded-lg border-2 border-gray-200 group-hover:border-primary-500 transition-all duration-200">
                                                                    <img src="' . asset('storage/' . $cleanPath) . '" 
                                                                         alt="' . ($media->alt_text ?? '') . '"
                                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                                                </div>
                                                                <div class="mt-2 text-center">
                                                                    <p class="text-sm font-medium text-gray-900 truncate">' . 
                                                                        Str::limit($media->original_filename, 25) . 
                                                                    '</p>
                                                                    <p class="text-xs text-gray-500">' . 
                                                                        ($media->dimensions ?? 'N/A') . ' • ' . ($media->size_human ?? 'N/A') .
                                                                    '</p>
                                                                </div>
                                                            </div>'
                                                        )
                                                    ];
                                                });
                                        })
                                        ->columns(4)
                                        ->gridDirection('row'),
                                ])
                                ->action(function (array $data, $set, $get) {
                                    if (!empty($data['selected_image'])) {
                                        // The path is already clean from the options array
                                        $set('featured_image', $data['selected_image']);
                                    }
                                })
                        )
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('short_description')
                        ->label('Short Description')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'link',
                            'bulletList',
                            'orderedList',
                        ])
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('content')
                        ->required()
                        ->toolbarButtons([
                            'attachFiles',
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('blog-content-images')
                        ->fileAttachmentsVisibility('public')
                        ->columnSpanFull()
                        ->helperText('You can upload images directly by clicking the attachment icon in the toolbar'),
                ])->columns(2),

            Section::make('Categories & Tags')
                ->schema([
                    Forms\Components\Select::make('categories')
                        ->relationship('categories', 'name')
                        ->multiple()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('description')
                                ->rows(3),
                        ]),

                    Forms\Components\Select::make('tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                        ]),
                ])->columns(2),

            Section::make('SEO Settings')
                ->schema([
                    Forms\Components\TextInput::make('meta_title')
                        ->maxLength(60)
                        ->helperText('Leave empty to use article title. Max 60 characters for optimal SEO.'),

                    Forms\Components\Textarea::make('meta_description')
                        ->rows(3)
                        ->maxLength(160)
                        ->helperText('Max 160 characters for optimal SEO.'),

                    Forms\Components\TagsInput::make('meta_keywords')
                        ->helperText('Add relevant keywords for SEO'),
                ])->columns(1)->collapsible(),

            Section::make('Publishing')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'scheduled' => 'Scheduled',
                            'published' => 'Published',
                        ])
                        ->required()
                        ->default('draft')
                        ->live(),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Publish Date & Time')
                        ->default(now())
                        ->required()
                        ->helperText('Set a future date for scheduled publishing'),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower numbers appear first. Use this to pin important articles.'),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'blog',
                        'success' => 'event',
                    ]),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'info' => 'scheduled',
                        'success' => 'published',
                    ]),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('comments_count')
                    ->label('Comments')
                    ->sortable(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'blog' => 'Blog',
                        'event' => 'Event',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'published' => 'Published',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogArticles::route('/'),
            'create' => Pages\CreateBlogArticle::route('/create'),
            'edit' => Pages\EditBlogArticle::route('/{record}/edit'),
        ];
    }
}