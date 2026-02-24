<?php

namespace App\Filament\Resources\BlogComments\Pages;

use App\Filament\Resources\BlogComments\BlogCommentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogComment extends CreateRecord
{
    protected static string $resource = BlogCommentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['ip_address'] = request()->ip();
        $data['user_agent'] = request()->userAgent();
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}