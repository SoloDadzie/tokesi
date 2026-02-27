<?php

namespace App\Http\Controllers;

use App\Models\BlogArticle;
use App\Models\BlogComment;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    public function index()
    {
        $articles = BlogArticle::published()
            ->ordered()
            ->with(['categories', 'tags'])
            ->paginate(12);

        // Get upcoming events only (events with published_at in the future)
        $upcomingEvents = BlogArticle::where('type', 'event')
            ->where('status', 'published')
            ->where('published_at', '>', now())
            ->orderBy('published_at', 'asc')
            ->get();

        return view('blogs.index', compact('articles', 'upcomingEvents'));
    }

    public function show($slug)
    {
        $article = BlogArticle::where('slug', $slug)
            ->published()
            ->with(['categories', 'tags', 'topLevelComments.replies'])
            ->firstOrFail();

        // Increment view count
        $article->incrementViewCount();

        // Get related articles
        $relatedArticles = BlogArticle::published()
            ->where('id', '!=', $article->id)
            ->where('type', $article->type)
            ->whereHas('categories', function ($query) use ($article) {
                $query->whereIn('blog_categories.id', $article->categories->pluck('id'));
            })
            ->take(3)
            ->get();

        return view('blogs.show', compact('article', 'relatedArticles'));
    }

    public function storeComment(Request $request, $slug)
    {
        $article = BlogArticle::where('slug', $slug)
            ->published()
            ->firstOrFail();

        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|max:255',
            'content' => 'required|string|max:5000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ]);

        $comment = $article->comments()->create([
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_admin' => false,
        ]);

        // Send email notification to admin
        try {
            Notification::route('mail', config('mail.from.address'))
                ->notify(new NewCommentNotification($comment));
        } catch (\Exception $e) {
            Log::error('Failed to send comment notification: ' . $e->getMessage());
        }

        return redirect()
            ->route('blog.show', $slug)
            ->with('success', 'Your comment has been posted successfully!')
            ->with('comment_id', $comment->id);
    }
}