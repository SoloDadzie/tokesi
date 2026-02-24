<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        // Clear the cache to force regeneration
        $cacheKey = config('sitemap.cache_key', 'laravel-sitemap.sitemap');
        Cache::forget($cacheKey);

        // Trigger the sitemap generation by calling the controller
        $controller = new \App\Http\Controllers\SitemapController();
        $response = $controller->index();

        $this->info('Sitemap generated successfully!');
        $this->info('Sitemap available at: ' . route('sitemap.index'));

        return Command::SUCCESS;
    }
}
