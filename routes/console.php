<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\BlogArticle;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    BlogArticle::where('status', 'scheduled')
        ->where('published_at', '<=', now())
        ->update(['status' => 'published']);
})->everyMinute();