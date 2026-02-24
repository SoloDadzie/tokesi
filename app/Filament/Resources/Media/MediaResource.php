<?php

namespace App\Filament\Resources\Media;

use App\Filament\Resources\Media\Pages;
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
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Filament\Actions\BulkAction;


class MediaResource extends Resource
{
    protected static ?string $model = Media::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Media Library';
    protected static ?string $modelLabel = 'Media';
    protected static string|\UnitEnum|null $navigationGroup = 'Content';
    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Upload Media')
                ->schema([
                    Forms\Components\FileUpload::make('path')
                        ->label('File(s)')
                        ->image()
                        ->disk('public')
                        ->directory('media-library')
                        ->imageEditor()
                        ->imageResizeMode('cover')
                        ->maxSize(10240)
                        ->multiple()
                        ->reorderable()
                        ->maxFiles(20)
                        ->required()
                        ->helperText('Upload up to 20 images at once. Each will be optimized automatically.')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                        ->columnSpanFull(),
                    
                    Forms\Components\Toggle::make('optimize_to_webp')
                        ->label('Optimize to WebP')
                        ->helperText('Convert images to WebP format for better performance (70% smaller file size)')
                        ->default(true)
                        ->columnSpanFull(),
                ]),

            Section::make('Media Details')
                ->schema([
                    Forms\Components\TextInput::make('alt_text')
                        ->label('Alt Text')
                        ->maxLength(255)
                        ->helperText('Describe the image for accessibility and SEO'),

                    Forms\Components\Textarea::make('caption')
                        ->rows(2)
                        ->maxLength(500),

                    Forms\Components\Select::make('collection')
                        ->options([
                            'general' => 'General',
                            'blog' => 'Blog',
                            'blog/featured' => 'Blog - Featured Images',
                            'blog/content' => 'Blog - Content Images',
                            'products' => 'Products',
                            'products/hero' => 'Products - Hero Images',
                            'products/gallery' => 'Products - Gallery',
                            'events' => 'Events',
                            'testimonials' => 'Testimonials',
                            'team' => 'Team Members',
                        ])
                        ->searchable()
                        ->default('general')
                        ->required()
                        ->helperText('Organize your media into folders'),

                    Forms\Components\TagsInput::make('tags')
                        ->helperText('Add tags for easier searching')
                        ->placeholder('Enter tags...')
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('Preview')
                    ->disk('public')
                    ->square()
                    ->size(60)
                    ->checkFileExistence(false),

                Tables\Columns\TextColumn::make('original_filename')
                    ->label('File Name')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->copyable()
                    ->copyMessage('Filename copied!')
                    ->description(fn (Media $record): string => $record->alt_text ?? ''),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'image',
                        'info' => 'video',
                        'warning' => 'document',
                    ]),

                Tables\Columns\TextColumn::make('collection')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->colors([
                        'primary' => 'blog',
                        'success' => 'products',
                        'warning' => 'events',
                        'info' => 'general',
                    ]),

                Tables\Columns\TextColumn::make('dimensions')
                    ->label('Dimensions')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('size_human')
                    ->label('File Size')
                    ->sortable(query: function ($query, $direction) {
                        return $query->orderBy('size', $direction);
                    }),

                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Used In')
                    ->getStateUsing(function (Media $record) {
                        $count = \App\Models\BlogArticle::where('featured_image', $record->path)
                            ->orWhere('content', 'like', '%' . $record->path . '%')
                            ->count();
                        return $count > 0 ? $count . ' articles' : 'Unused';
                    })
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Unused' ? 'gray' : 'success')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'image' => 'Image',
                        'video' => 'Video',
                        'document' => 'Document',
                    ]),

                Tables\Filters\SelectFilter::make('collection')
                    ->options([
                        'general' => 'General',
                        'blog' => 'Blog (All)',
                        'blog/featured' => 'Blog - Featured',
                        'blog/content' => 'Blog - Content',
                        'products' => 'Products (All)',
                        'products/hero' => 'Products - Hero',
                        'products/gallery' => 'Products - Gallery',
                        'events' => 'Events',
                        'testimonials' => 'Testimonials',
                        'team' => 'Team Members',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('unused')
                    ->label('Show Only Unused')
                    ->query(function ($query) {
                        return $query->whereDoesntHave('blogArticles');
                    }),

                Tables\Filters\SelectFilter::make('size_range')
                    ->label('File Size')
                    ->options([
                        'small' => 'Small (< 500KB)',
                        'medium' => 'Medium (500KB - 2MB)',
                        'large' => 'Large (> 2MB)',
                    ])
                    ->query(function ($query, $state) {
                        return match($state['value'] ?? null) {
                            'small' => $query->where('size', '<', 512000),
                            'medium' => $query->whereBetween('size', [512000, 2097152]),
                            'large' => $query->where('size', '>', 2097152),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                Action::make('copy_url')
                    ->label('Copy URL')
                    ->icon('heroicon-o-clipboard')
                    ->color('info')
                    ->modalHeading('Image URL')
                    ->modalWidth('md')
                    ->form([
                        Forms\Components\TextInput::make('url')
                            ->label('Full URL')
                            ->default(fn (Media $record) => asset('storage/' . $record->path))
                            ->readOnly()
                            ->suffixAction(
                            Action::make('copy')
                                    ->icon('heroicon-o-clipboard')
                                    ->extraAttributes([
                                        'onclick' => 'navigator.clipboard.writeText(this.closest(".fi-form-field").querySelector("input").value); 
                                                     this.querySelector("svg").style.color = "rgb(34, 197, 94)";
                                                     setTimeout(() => this.querySelector("svg").style.color = "", 1000);'
                                    ])
                            ),
                        Forms\Components\TextInput::make('relative_path')
                            ->label('Relative Path')
                            ->default(fn (Media $record) => $record->path)
                            ->readOnly()
                            ->helperText('Use this in Filament fields'),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                Action::make('show_usage')
                    ->label('Where Used')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading('Media Usage')
                    ->modalWidth('lg')
                    ->form(function (Media $record) {
                        $articles = \App\Models\BlogArticle::where('featured_image', $record->path)
                            ->orWhere('content', 'like', '%' . $record->path . '%')
                            ->get();
                        
                        if ($articles->isEmpty()) {
                            return [
                                Forms\Components\Placeholder::make('no_usage')
                                    ->label('')
                                    ->content('This media is not currently used in any articles.')
                            ];
                        }
                        
                        $articlesHtml = '<div class="space-y-3">';
                        foreach ($articles as $article) {
                            $articlesHtml .= '<div class="rounded-lg border p-3">';
                            $articlesHtml .= '<div class="font-medium">' . e($article->title) . '</div>';
                            $articlesHtml .= '<div class="text-sm text-gray-500 mt-1">ID: ' . $article->id . '</div>';
                            
                            // Determine usage type
                            $usageType = [];
                            if ($article->featured_image === $record->path) {
                                $usageType[] = 'Featured Image';
                            }
                            if (str_contains($article->content ?? '', $record->path)) {
                                $usageType[] = 'In Content';
                            }
                            $articlesHtml .= '<div class="text-xs text-blue-600 mt-1">' . implode(', ', $usageType) . '</div>';
                            $articlesHtml .= '</div>';
                        }
                        $articlesHtml .= '</div>';
                        
                        return [
                            Forms\Components\Placeholder::make('usage_list')
                                ->label('Used in ' . $articles->count() . ' article(s)')
                                ->content(new \Illuminate\Support\HtmlString($articlesHtml))
                        ];
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                EditAction::make(),

                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure? This action cannot be undone.')
                    ->before(function (Media $record) {
                        $usageCount = \App\Models\BlogArticle::where('featured_image', $record->path)
                            ->orWhere('content', 'like', '%' . $record->path . '%')
                            ->count();
                        
                        if ($usageCount > 0) {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title('Media in use')
                                ->body("This media is used in {$usageCount} article(s). Deleting it may break your content.")
                                ->persistent()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('optimize_to_webp')
                        ->label('Optimize to WebP')
                        ->icon('heroicon-o-sparkles')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalDescription('Convert selected images to WebP format for better performance. This will replace the original files.')
                        ->action(function ($records) {
                            $manager = new ImageManager(new Driver());
                            $success = 0;
                            
                            foreach ($records as $record) {
                                if ($record->type !== 'image') continue;
                                
                                try {
                                    $fullPath = Storage::disk('public')->path($record->path);
                                    $image = $manager->read($fullPath);
                                    
                                    $newFilename = Str::replaceLast(
                                        '.' . pathinfo($record->path, PATHINFO_EXTENSION),
                                        '.webp',
                                        $record->path
                                    );
                                    
                                    $newPath = Storage::disk('public')->path($newFilename);
                                    $image->toWebp(85)->save($newPath);
                                    
                                    Storage::disk('public')->delete($record->path);
                                    
                                    $record->update([
                                        'path' => $newFilename,
                                        'filename' => basename($newFilename),
                                        'mime_type' => 'image/webp',
                                        'size' => filesize($newPath),
                                    ]);
                                    
                                    $success++;
                                } catch (\Exception $e) {
                                    // Continue with next image
                                }
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Optimization complete')
                                ->body("{$success} images converted to WebP successfully.")
                                ->send();
                        }),
                    
                    BulkAction::make('move_to_collection')
                        ->label('Move to Folder')
                        ->icon('heroicon-o-folder')
                        ->color('info')
                        ->form([
                            Forms\Components\Select::make('collection')
                                ->label('Target Folder')
                                ->options([
                                    'general' => 'General',
                                    'blog' => 'Blog',
                                    'blog/featured' => 'Blog - Featured',
                                    'blog/content' => 'Blog - Content',
                                    'products' => 'Products',
                                    'products/hero' => 'Products - Hero',
                                    'products/gallery' => 'Products - Gallery',
                                    'events' => 'Events',
                                    'testimonials' => 'Testimonials',
                                    'team' => 'Team Members',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->update(['collection' => $data['collection']]);
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Moved successfully')
                                ->body(count($records) . ' items moved to ' . $data['collection'])
                                ->send();
                        }),

                    DeleteBulkAction::make()
                        ->modalDescription('Warning: Check if these media files are in use before deleting!'),
                ]),
            ])
            ->toolbarActions([
                Action::make('clean_unused')
                    ->label('Clean Unused Media')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete All Unused Media')
                    ->modalDescription('This will permanently delete all media files that are not used in any articles. This action cannot be undone!')
                    ->action(function () {
                        $unusedMedia = Media::whereDoesntHave('blogArticles')->get();
                        $count = $unusedMedia->count();
                        
                        foreach ($unusedMedia as $media) {
                            Storage::disk('public')->delete($media->path);
                            $media->delete();
                        }
                        
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Cleanup complete')
                            ->body("{$count} unused media files deleted.")
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }
}