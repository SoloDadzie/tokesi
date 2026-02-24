<?php

namespace App\Filament\Resources\BlogComments;


use App\Filament\Resources\BlogComments\Pages;
use App\Models\BlogComment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

class BlogCommentResource extends Resource
{
    protected static ?string $model = BlogComment::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Comments';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Reply to Comment')
                ->schema([
                    Forms\Components\Select::make('blog_article_id')
                        ->relationship('article', 'title')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->disabled(fn ($record) => $record !== null),

                    Forms\Components\Select::make('parent_id')
                        ->label('Reply To (Optional)')
                        ->relationship('parent', 'author_name')
                        ->searchable()
                        ->preload()
                        ->helperText('Select a comment to reply to, or leave empty for a new comment'),

                    Forms\Components\TextInput::make('author_name')
                        ->required()
                        ->default(fn () => 'Tokesi Akinola (Admin)')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('author_email')
                        ->email()
                        ->required()
                        ->default(fn () => config('mail.from.address'))
                        ->maxLength(255),

                    Forms\Components\Textarea::make('content')
                        ->required()
                        ->rows(5)
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('is_admin')
                        ->label('Mark as Admin Reply')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('article.title')
                    ->label('Article')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('author_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author_email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('content')
                    ->limit(50)
                    ->wrap(),

                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean(),

                Tables\Columns\TextColumn::make('parent.author_name')
                    ->label('Reply To')
                    ->default('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('is_admin')
                    ->label('Admin Comments')
                    ->query(fn (Builder $query): Builder => $query->where('is_admin', true)),
                Tables\Filters\Filter::make('replies')
                    ->label('Replies Only')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('parent_id')),
            ])
            ->recordActions([
                Action::make('reply')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->url(fn (BlogComment $record): string => 
                        static::getUrl('create', ['parent_id' => $record->id, 'blog_article_id' => $record->blog_article_id])
                    ),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogComments::route('/'),
            'create' => Pages\CreateBlogComment::route('/create'),
            'edit' => Pages\EditBlogComment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
{
    return (string) BlogComment::count();
}
}