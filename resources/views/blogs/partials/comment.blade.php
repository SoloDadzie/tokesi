{{-- resources/views/blogs/partials/comment.blade.php --}}

<div id="comment-{{ $comment->id }}" class="flex gap-[20px] {{ $level > 0 ? 'ml-[50px] md:ml-[80px]' : '' }}">
  <div class="flex-shrink-0">
    <div class="w-[50px] h-[50px] rounded-full bg-gradient-to-br from-[#d67a00] to-[#b56600] flex items-center justify-center text-white text-[1.2rem] font-bold">
      {{ strtoupper(substr($comment->author_name, 0, 1)) }}
    </div>
  </div>

  <div class="flex-1">
    <div class="mb-[10px]">
      <div class="flex items-center gap-[10px] mb-[5px] flex-wrap">
        <span class="font-sans font-bold text-[1rem] {{ $comment->is_admin ? 'text-[#d67a00]' : 'text-[#1a1a1a]' }}">
          {{ $comment->author_name }}
          @if($comment->is_admin)
          <span class="ml-[5px] px-[8px] py-[2px] bg-[#d67a00] text-white text-[0.7rem] uppercase rounded-[2px]">Admin</span>
          @endif
        </span>
        <span class="text-[#717171] text-[0.85rem]">
          {{ $comment->created_at->diffForHumans() }}
        </span>
      </div>

      @if($comment->parent && $level === 1)
      <p class="text-[#717171] text-[0.85rem] italic mb-[10px]">
        Replying to {{ $comment->parent->author_name }}
      </p>
      @endif
    </div>

    <div class="mb-[15px] text-[#333] leading-relaxed">
      {{ $comment->content }}
    </div>

    <button 
      onclick="replyToComment({{ $comment->id }}, '{{ addslashes($comment->author_name) }}')"
      class="text-[#d67a00] text-[0.9rem] font-medium hover:underline hover:text-[#b56600] transition-colors">
      Reply
    </button>

    {{-- Nested Replies --}}
    @if($comment->replies->count() > 0 && $level < 2)
    <div class="mt-[30px] space-y-[30px]">
      @foreach($comment->replies as $reply)
        @include('blogs.partials.comment', ['comment' => $reply, 'level' => $level + 1])
      @endforeach
    </div>
    @endif
  </div>
</div>