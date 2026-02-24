<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CartService;
use App\Models\BlogArticle;
use App\Observers\BlogArticleObserver;
use App\Observers\OrderObserver;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        // Register CartService as singleton
        $this->app->singleton(CartService::class);

        $this->app->singleton(\App\Services\ImageService::class, function ($app) {
            return new \App\Services\ImageService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       // Register observer for automatic WebP conversion
        BlogArticle::observe(BlogArticleObserver::class);

        // Publish scheduled posts automatically
        $this->publishScheduledPosts();

        Order::observe(OrderObserver::class);
    }


    /**
     * Automatically publish scheduled posts
     */
    protected function publishScheduledPosts(): void
    {
        if (!app()->runningInConsole()) {
            BlogArticle::where('status', 'scheduled')
                ->where('published_at', '<=', now())
                ->update(['status' => 'published']);
        }
    }

}
