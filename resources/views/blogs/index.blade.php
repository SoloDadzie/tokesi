{{-- resources/views/blogs/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Tokesi Akinola Blog & Event| Tips, Stories & Children’s Reading Inspiration')
@section('meta_description', 'Explore kid-friendly reading tips, parenting insights, and writing updates from Wigan children’s author Tokesi Akinola.')


@section('content')
<section class="page-hero">
    <div class="page-hero-container">
        <h1 class="page-hero-title">Blogs & Events</h1>
        <p class="page-hero-subtitle">Here you can read insightful articles and view our recent events</p>
    </div>
    <div class="page-hero-decoration"></div>
</section>

<section class="blog-section">
    <div class="blog-container">
        @if($articles->count() > 0)
            <div class="blog-grid">
                @foreach($articles as $article)
                    <article class="blog-card">
                        <div class="blog-image">
                            <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : asset('imgs/book-show-me-your-friends.jpg') }}" alt="{{ $article->title }}">
                            <span class="blog-category">{{ ucfirst($article->type) }}</span>
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-date">{{ $article->published_at?->format('F j, Y') }}</span>
                                <span class="blog-comments">{{ $article->comments_count }} {{ Str::plural('Comment', $article->comments_count) }}</span>
                            </div>
                            <h3 class="blog-title">{{ $article->title }}</h3>
                            <p class="blog-excerpt">{!! Str::limit(strip_tags($article->short_description ?? $article->content), 150) !!}</p>
                            <a href="{{ route('blog.show', $article->slug) }}" class="btn btn-outline">Read More</a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div style="margin-top: 2rem;">
                {{ $articles->links() }}
            </div>
        @else
            <p class="blog-excerpt" style="text-align: center;">No articles found.</p>
        @endif
    </div>
</section>

<section class="events-section">
    <div class="events-container">
        <h2 class="section-title">Upcoming Events</h2>
        <div class="events-grid">
            <div class="event-card">
                <div class="event-date-badge"><span class="event-day">15</span><span class="event-month">Feb</span></div>
                <div class="event-content">
                    <h3 class="event-title">Book Reading & Signing</h3>
                    <p class="event-description">Join Tokesi for an afternoon of storytelling and book signing.</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline">Learn More</a>
                </div>
            </div>
            <div class="event-card">
                <div class="event-date-badge"><span class="event-day">22</span><span class="event-month">Mar</span></div>
                <div class="event-content">
                    <h3 class="event-title">School Visit Programme</h3>
                    <p class="event-description">Tokesi visits local schools to inspire the next generation of readers.</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline">Book a Visit</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection