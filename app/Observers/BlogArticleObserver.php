<?php

namespace App\Observers;

use App\Models\BlogArticle;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class BlogArticleObserver
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function created(BlogArticle $article): void
    {
        // Convert featured image to WebP
        if ($article->featured_image) {
            $webpPath = $this->imageService->processUploadedImage($article->featured_image, [
                'quality' => 90,
                'max_width' => 1920,
                'max_height' => 1080,
            ]);
            $article->updateQuietly(['featured_image' => $webpPath]);
        }
    }

    public function updated(BlogArticle $article): void
    {
        // Convert new featured image to WebP if changed
        if ($article->isDirty('featured_image') && $article->featured_image) {
            $oldImage = $article->getOriginal('featured_image');
            
            // Delete old image
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            // Convert new image
            $webpPath = $this->imageService->processUploadedImage($article->featured_image, [
                'quality' => 90,
                'max_width' => 1920,
                'max_height' => 1080,
            ]);
            $article->updateQuietly(['featured_image' => $webpPath]);
        }
    }

    public function deleted(BlogArticle $article): void
    {
        // Delete featured image
        if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
            Storage::disk('public')->delete($article->featured_image);
        }
    }
}