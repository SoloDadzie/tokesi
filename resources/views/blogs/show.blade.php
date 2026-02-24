{{-- resources/views/blogs/show.blade.php --}}

@extends('layouts.app')

@section('title', $article->meta_title ?? $article->title)
@section('meta_description', $article->meta_description ?? Str::limit(strip_tags($article->short_description ?? $article->content), 160))

@push('head')
<script type="application/ld+json">
{!! json_encode($article->getSchemaMarkup()) !!}
</script>
@endpush

@section('content')

<section class="bg-white">
  <!-- Header Section -->
  <div class="bg-gray-50 py-12 px-4">
    <div class="max-w-7xl mx-auto mt-12 px-[25px] md:px-[40px] lg:px-[50px]">
      <h2 class="text-xl md:text-xl font-semi-bold text-gray-500 mb-3">Blogs & Events</h2>
      
      <!-- Title -->
      <h1 class="blog-detail-title font-serif text-3xl md:text-5xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>
      
      <!-- Breadcrumb -->
      <nav class="text-sm text-gray-600">
        <a href="{{ route('home') }}" class="hover:text-[#d67a00] transition-colors">Home</a>
        <span class="mx-2">|</span>
        @foreach($article->categories as $category)
          <a href="{{ route('blog.index') }}?category={{ $category->slug }}" class="hover:text-[#d67a00] transition-colors">{{ $category->name }}</a>
          @if(!$loop->last)
            <span class="mx-2">|</span>
          @endif
        @endforeach
        @if($article->categories->count() > 0)
          <span class="mx-2">|</span>
        @endif
        <span class="text-gray-900">{{ Str::limit($article->title, 50) }}</span>
      </nav>
    </div>
  </div>

  <!-- Main Content -->
  <div class="max-w-7xl mx-auto px-[25px] lg:px-[70px] py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Left Column -->
      <div class="lg:col-span-8 lg:pr-8 lg:border-r border-gray-200">
        <!-- Short Description -->
        @if($article->short_description)
        <div class="text-lg text-gray-600 mb-6">
          {!! $article->short_description !!}
        </div>
        @endif
        
        <!-- Blog Image -->
        @if($article->featured_image)
        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-auto rounded-[4px] mb-6">
        @endif
        
        <!-- Meta Information -->
        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 mb-8">
          <span class="bg-gray-100 px-3 py-1 rounded-[4px]">{{ ucfirst($article->type) }}</span>
          @foreach($article->categories as $category)
          <span class="bg-gray-100 px-3 py-1 rounded-[4px]">{{ $category->name }}</span>
          @endforeach
          <span>•</span>
          <time datetime="{{ $article->published_at->toIso8601String() }}">
            {{ $article->published_at->format('F j, Y') }}
          </time>
          <span>•</span>
          <span>{{ $article->author_name }}</span>
          <span>•</span>
          <span>{{ $article->comments_count }} Comment{{ $article->comments_count != 1 ? 's' : '' }}</span>
        </div>
        
        <!-- Main Content -->
        <div class="prose max-w-none mb-12">
          {!! $article->content !!}
        </div>
        
        <!-- Tags -->
        @if($article->tags->count() > 0)
        <div class="mb-12 pb-8 border-b border-gray-200">
          <div class="flex flex-wrap items-center gap-2">
            <span class="font-semibold text-gray-900">Tags:</span>
            @foreach($article->tags as $tag)
            <a href="{{ route('blog.index') }}?tag={{ $tag->slug }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-[4px] text-sm hover:bg-gray-200 transition-colors">
              {{ $tag->name }}
            </a>
            @endforeach
          </div>
        </div>
        @endif
        
        <!-- Comments Section -->
        <div class="comments-section border-t border-gray-200 pt-8 mb-8">
          <h3 class="text-2xl font-bold text-gray-900 mb-6">Comments ({{ $article->comments_count }})</h3>
          
          <!-- Success Message -->
          @if(session('success'))
          <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-[4px]">
            {{ session('success') }}
          </div>
          @endif
          
          <!-- Comments List -->
          <div class="space-y-[30px] mb-8">
            @forelse($article->topLevelComments as $comment)
              @include('blogs.partials.comment', ['comment' => $comment, 'level' => 0])
            @empty
            <p class="text-gray-500 text-center py-8">No comments yet. Be the first to comment!</p>
            @endforelse
          </div>
        </div>
        
        <!-- Leave a Comment Form -->
        <div class="bg-gray-50 p-6 rounded-[4px]">
          <h3 class="text-2xl font-bold text-gray-900 mb-6">Leave a Comment</h3>
          
          <form method="POST" action="{{ route('blog.comment.store', $article->slug) }}" id="comment-form">
            @csrf
            <input type="hidden" name="parent_id" id="parent_id" value="">
            
            <div id="reply-info" class="hidden mb-4 p-4 bg-[#fff3e6] border border-[#d67a00] rounded-[4px] flex justify-between items-center">
              <span class="text-[#21263a]">Replying to <strong id="reply-to-name"></strong></span>
              <button type="button" onclick="cancelReply()" class="text-[#d67a00] hover:text-[#b56600] font-medium">Cancel</button>
            </div>
            
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2" for="author_name">Name *</label>
              <input type="text" id="author_name" name="author_name" value="{{ old('author_name') }}" class="w-full px-4 py-3 border border-gray-300 rounded-[4px] focus:outline-none focus:border-[#d67a00]" required>
              @error('author_name')
              <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
              @enderror
            </div>
            
            <div class="mb-4">
              <label class="block text-gray-700 font-medium mb-2" for="author_email">Email *</label>
              <input type="email" id="author_email" name="author_email" value="{{ old('author_email') }}" class="w-full px-4 py-3 border border-gray-300 rounded-[4px] focus:outline-none focus:border-[#d67a00]" required>
              @error('author_email')
              <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
              @enderror
            </div>
            
            <div class="mb-6">
              <label class="block text-gray-700 font-medium mb-2" for="content">Message *</label>
              <textarea id="content" name="content" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-[4px] focus:outline-none focus:border-[#d67a00] resize-none" required>{{ old('content') }}</textarea>
              @error('content')
              <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
              @enderror
            </div>
            
            <button type="submit" id="submit-comment-btn" class="relative bg-[#21263a] text-white px-8 py-3 rounded-[4px] font-medium overflow-hidden group transition-opacity duration-300">
              <span class="relative z-10">Post Comment</span>
              <span class="absolute inset-0 bg-[#d67a00] transform translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
            </button>
          </form>
        </div>
      </div>
      
      <!-- Right Column -->
      <div class="lg:col-span-4">
        <!-- Related/Popular Posts -->
        @if($relatedArticles->count() > 0)
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Related Articles</h3>
          
          @foreach($relatedArticles as $related)
          <a href="{{ route('blog.show', $related->slug) }}" class="flex gap-4 mb-6 pb-6 border-b border-gray-100 group last:border-b-0">
            @if($related->featured_image)
            <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-24 h-20 object-cover rounded-[4px] flex-shrink-0">
            @else
            <div class="w-24 h-20 bg-gray-200 rounded-[4px] flex-shrink-0"></div>
            @endif
            <div>
              <h4 class="font-semibold text-gray-900 mb-2 group-hover:text-[#d67a00] transition-colors">{{ $related->title }}</h4>
              <p class="text-sm text-gray-500">{{ $related->published_at->format('F j, Y') }}</p>
            </div>
          </a>
          @endforeach
        </div>
        @endif
        
        <!-- Author Box -->
        <div class="bg-[#21263a] text-white p-6 mb-8 rounded-[4px]">
          <h3 class="text-xl font-bold mb-3">{{ $article->author_name }}</h3>
          <p class="text-gray-300 mb-4 text-sm leading-relaxed">Award-winning author passionate about creating engaging content that inspires and educates readers.</p>
          <a href="{{ route('blog.index') }}?author={{ urlencode($article->author_name) }}" class="relative bg-white text-[#21263a] px-6 py-2 rounded-[4px] font-medium overflow-hidden group w-full block text-center">
            <span class="relative z-10">View More Posts</span>
            <span class="absolute inset-0 bg-[#d67a00] text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300 flex items-center justify-center">View More Posts</span>
          </a>
        </div>
        
        <!-- Post Categories -->
        @if($article->categories->count() > 0)
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Post Categories</h3>
          <div class="space-y-0">
            @foreach($article->categories as $category)
            <a href="{{ route('blog.index') }}?category={{ $category->slug }}" class="flex justify-between items-center py-3 border-b border-gray-200 hover:text-[#d67a00] transition-colors">
              <span>{{ $category->name }}</span>
              <span class="text-gray-500">({{ $category->articles()->published()->count() }})</span>
            </a>
            @endforeach
          </div>
        </div>
        @endif
        
        <!-- Post Tags -->
        @if($article->tags->count() > 0)
        <div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Post Tags</h3>
          <div class="flex flex-wrap gap-2">
            @foreach($article->tags as $tag)
            <a href="{{ route('blog.index') }}?tag={{ $tag->slug }}" class="px-4 py-2 border border-gray-300 rounded-[4px] text-sm hover:border-[#d67a00] hover:text-[#d67a00] transition-colors">{{ $tag->name }}</a>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>

<script>
function replyToComment(commentId, authorName) {
  document.getElementById('parent_id').value = commentId;
  document.getElementById('reply-to-name').textContent = authorName;
  document.getElementById('reply-info').classList.remove('hidden');
  document.getElementById('comment-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function cancelReply() {
  document.getElementById('parent_id').value = '';
  document.getElementById('reply-info').classList.add('hidden');
}

// Fade out the submit button on click to prevent multiple submissions
document.getElementById('submit-comment-btn').addEventListener('click', function(e) {
  const button = this;
  // Add fade-out effect
  button.classList.add('opacity-50', 'cursor-not-allowed');
  button.disabled = true;
  
  // Optional: re-enable button after 5 seconds in case there's an error
  setTimeout(function() {
    button.classList.remove('opacity-50', 'cursor-not-allowed');
    button.disabled = false;
  }, 5000);
});

// Scroll to comment if redirected after posting
@if(session('comment_id'))
window.addEventListener('load', function() {
  const commentElement = document.getElementById('comment-{{ session("comment_id") }}');
  if (commentElement) {
    commentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    commentElement.classList.add('highlight-comment');
  }
});
@endif
</script>

<style>
.highlight-comment {
  animation: highlight 2s ease-in-out;
}

@keyframes highlight {
  0%, 100% { background-color: transparent; }
  50% { background-color: #fff3e6; }
}

.prose img {
  max-width: 100%;
  height: auto;
  margin: 2rem auto;
  display: block;
  border-radius: 4px;
}

.prose h2 {
  font-size: 1.8rem;
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: bold;
  color: #1a1a1a;
}

.prose h3 {
  font-size: 1.4rem;
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
  font-weight: bold;
  color: #1a1a1a;
}

.prose p {
  color: #374151;
  line-height: 1.75;
  margin-bottom: 1.5rem;
}

.prose ul, .prose ol {
  margin: 1.5rem 0;
  padding-left: 2rem;
  color: #374151;
}

.prose li {
  margin-bottom: 0.5rem;
  line-height: 1.75;
}

.prose a {
  color: #d67a00;
  text-decoration: underline;
}

.prose a:hover {
  color: #b56600;
}
</style>

@endsection