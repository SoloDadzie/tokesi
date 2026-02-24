<?php
namespace App\Filament\Resources\BlogComments\Pages;

use App\Filament\Resources\BlogComments\BlogCommentResource;
use Filament\Resources\Pages\ListRecords;

class ListBlogComments extends ListRecords
{
    protected static string $resource = BlogCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}