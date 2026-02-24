@extends('layouts.app')

{{-- Dynamic page title for SEO --}}
@section('title', $title)
@section('meta_description', $metaDescription)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-20">
    {{-- Hero Section --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4">
            Children's Book Author in {{ ucfirst($location) }}
        </h1>
        <p class="text-lg text-gray-700">
            Discover engaging children’s books, events, and activities in {{ ucfirst($location) }} by award-winning children author Tokesi Akinola.
        </p>        <div class="mt-6">
            <a href="{{ route('home') }}" class="inline-block text-[#d67a00] hover:text-[#b56600] font-medium transition-colors">
                ← Back to Welcome Page
            </a>
        </div>    </div>

    {{-- Featured Books Section --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-6">Featured Books</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse(\App\Models\Product::where('is_featured', true)->take(6)->get() as $book)
                <div class="border rounded-lg shadow p-4 hover:shadow-lg transition-shadow">
                    @if($book->primaryImage)
                        <img src="{{ asset('storage/' . $book->primaryImage->image_path) }}" alt="{{ $book->title }}" class="w-full h-48 object-cover rounded mb-4">
                    @else
                        <div class="w-full h-48 bg-gray-200 rounded mb-4 flex items-center justify-center">
                            <span class="text-gray-400">No Image</span>
                        </div>
                    @endif
                    <h3 class="font-bold text-lg mb-2">{{ $book->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($book->short_description, 100) }}</p>
                    <p class="text-[#d67a00] font-semibold mb-4">${{ number_format($book->price, 2) }}</p>
                    <a href="{{ route('product.show', $book->slug) }}" class="inline-block bg-[#21263a] text-white px-4 py-2 rounded hover:bg-[#d67a00] transition-colors text-sm font-medium">
                        View Book
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-8">
                    <p>Featured books coming soon!</p>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('shop.index') }}" class="relative bg-[#21263a] text-white px-8 py-3 rounded font-medium overflow-hidden group inline-block">
                <span class="relative z-10">Browse All Books</span>
                <span class="absolute inset-0 bg-[#d67a00] transform translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
            </a>
        </div>
    </section>

    {{-- About Us Section --}}
    <section class="mb-12 bg-gray-50 p-8 rounded-lg">
        <h2 class="text-2xl font-semibold mb-6">About Tokesi Akinola</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    Tokesi Akinola is an award-winning children's author dedicated to inspiring young readers through engaging and meaningful stories. With a Master's degree in Early Childhood Education, Tokesi combines pedagogical expertise with creative storytelling to create books that educate and entertain.
                </p>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    Featured in The Guardian and recognized for school visits and author talks, Tokesi's passion is to spark imagination and foster a love of reading in children across the UK and beyond.
                </p>
                <a href="{{ route('about') }}" class="inline-block text-[#d67a00] hover:text-[#b56600] font-medium transition-colors">
                    Learn More About the Author →
                </a>
            </div>
            <div>
                <div class="bg-[#21263a] text-white p-6 rounded-lg">
                    <h3 class="text-xl font-bold mb-4">Why Choose Tokesi's Books?</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-[#d67a00] mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Award-winning children's literature</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-[#d67a00] mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Educational value with entertainment</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-[#d67a00] mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Stories that inspire imagination</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-[#d67a00] mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Perfect for school and home reading</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Read Tokesi Akinola Books Section --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-6 text-center">Why Read Tokesi Akinola Books?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white border rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="bg-[#d67a00] text-white w-12 h-12 rounded-full flex items-center justify-center mb-4 text-xl font-bold">
                    ✨
                </div>
                <h3 class="font-bold text-lg mb-3">Spark Imagination</h3>
                <p class="text-gray-600">Award-winning stories that transport children to magical worlds and inspire their creativity.</p>
            </div>
            <div class="bg-white border rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="bg-[#d67a00] text-white w-12 h-12 rounded-full flex items-center justify-center mb-4 text-xl font-bold">
                    📚
                </div>
                <h3 class="font-bold text-lg mb-3">Educational Value</h3>
                <p class="text-gray-600">Stories crafted with expertise in early childhood education to promote learning and development.</p>
            </div>
            <div class="bg-white border rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="bg-[#d67a00] text-white w-12 h-12 rounded-full flex items-center justify-center mb-4 text-xl font-bold">
                    💝
                </div>
                <h3 class="font-bold text-lg mb-3">Build Connections</h3>
                <p class="text-gray-600">Perfect for family reading time and creating lasting memories with your children.</p>
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('shop.index') }}" class="relative bg-[#d67a00] text-white px-8 py-3 rounded font-medium overflow-hidden group inline-block">
                <span class="relative z-10">Explore Featured Books</span>
                <span class="absolute inset-0 bg-[#21263a] transform translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
            </a>
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-6">What Readers Say</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse(\App\Models\Testimonial::where('is_active', true)->orderBy('order')->get() as $testimonial)
                <blockquote class="border-l-4 border-[#d67a00] pl-6 italic text-gray-700 bg-gray-50 p-6 rounded">
                    <p class="mb-3">"{{ $testimonial->text }}"</p>
                    <footer class="not-italic text-gray-600 text-sm">
                        – {{ $testimonial->name }}
                        @if($testimonial->location)
                            <span class="block">{{ $testimonial->location }}</span>
                        @endif
                    </footer>
                </blockquote>
            @empty
                <p class="col-span-full text-gray-500 text-center py-8">Testimonials coming soon!</p>
            @endforelse
        </div>
    </section>

    {{-- Blog/Articles Section --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-6">Latest Blog Posts & Articles</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse(\App\Models\BlogArticle::where('status', 'published')->latest('published_at')->take(3)->get() as $article)
                <a href="{{ route('blog.show', $article->slug) }}" class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow group">
                    <div class="relative overflow-hidden bg-gray-200 h-48">
                        @if($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <span class="inline-block bg-[#d67a00] text-white text-xs font-medium px-3 py-1 rounded mb-3">
                            {{ $article->type }}
                        </span>
                        <h3 class="font-bold text-lg mb-2 group-hover:text-[#d67a00] transition-colors line-clamp-2">
                            {{ $article->title }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ Str::limit(strip_tags($article->short_description ?? $article->content), 100) }}
                        </p>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>{{ $article->published_at->format('M j, Y') }}</span>
                            <span>{{ $article->comments_count }} Comments</span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-gray-500 text-center py-8">Blog posts coming soon!</p>
            @endforelse
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('blog.index') }}" class="relative bg-[#21263a] text-white px-8 py-3 rounded font-medium overflow-hidden group inline-block">
                <span class="relative z-10">Read More Articles</span>
                <span class="absolute inset-0 bg-[#d67a00] transform translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
            </a>
        </div>
    </section>

  
</div>
@endsection
