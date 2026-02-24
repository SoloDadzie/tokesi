<?php

namespace App\Notifications;

use App\Models\BlogComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public function __construct(public BlogComment $comment)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $article = $this->comment->article;
        $isReply = $this->comment->isReply();

        $subject = $isReply 
            ? 'New Reply on: ' . $article->title
            : 'New Comment on: ' . $article->title;

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello!')
            ->line($isReply ? 'Someone replied to a comment on your article.' : 'Someone commented on your article.')
            ->line('**Article:** ' . $article->title)
            ->line('**Author:** ' . $this->comment->author_name)
            ->line('**Email:** ' . $this->comment->author_email);

        if ($isReply) {
            $mail->line('**Replying to:** ' . $this->comment->parent->author_name);
        }

        $mail->line('**Comment:**')
            ->line($this->comment->content)
            ->action('View Comment', route('blog.show', $article->slug) . '#comment-' . $this->comment->id)
            ->line('You can manage this comment from your admin panel.');

        return $mail;
    }
}