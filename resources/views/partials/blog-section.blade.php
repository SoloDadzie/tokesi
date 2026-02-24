{{-- resources/views/partials/blog-section.blade.php --}}

@php
    $articles = \App\Models\BlogArticle::published()
        ->ordered()
        ->with(['categories', 'tags'])
        ->take(6)
        ->get();
@endphp

@if($articles->count() > 0)
<section class="w-full overflow-hidden mt-[70px]">
  <div class="flex flex-col text-center mb-[50px] gap-[10px]">
    <h2 class="font-serif text-[2rem]">Blogs & Events</h2>
    <p class="font-sans">Here you can read insightful articles and also view our recent events</p>
  </div>

  <div class="px-[25px] grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[20px] md:px-[40px] lg:px-[70px]">
    @foreach($articles as $article)
    <a href="{{ route('blog.show', $article->slug) }}" class="group">
      <div>
        <img 
          src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : asset('image/default-blog.jpg') }}"
          alt="{{ $article->title }}"
          loading="lazy"
          class="w-full h-[220px] object-cover md:h-[200px] sm:h-[200px] xs:h-[180px] group-hover:opacity-90 transition-opacity">

        <div class="py-[20px] flex flex-col gap-[5px] font-sans">
          <span class="text-[#717171] text-[0.8rem] italic">
            On {{ $article->published_at->format('jS M, Y') }}
          </span>

          <h3 class="font-serif text-[1.2rem] sm:text-[1.1rem] group-hover:text-[#d67a00] transition-colors">
            {{ $article->title }}
          </h3>

          <div class="w-full h-[1px] bg-[#717171]"></div>

          <div class="flex items-center gap-[15px] font-sans">
            <span class="pr-[15px] border-r border-[#717171] text-[#1a1a1a] font-medium capitalize">
              {{ $article->type }}
            </span>
            <span class="text-[#1a1a1a] font-medium">
              {{ $article->comments_count }} {{ Str::plural('Comment', $article->comments_count) }}
            </span>
          </div>
        </div>
      </div>
    </a>
    @endforeach
  </div>

  @php
    $totalPublished = \App\Models\BlogArticle::published()->count();
  @endphp

  @if($totalPublished > 6)
  <div class="flex justify-center mt-[40px]">
    <a href="{{ route('blog.index') }}" 
       class="px-[30px] py-[12px] bg-[#1a1a1a] text-white font-sans font-medium hover:bg-[#333] transition-colors">
      View More
    </a>
  </div>
  @endif
</section>
@endif