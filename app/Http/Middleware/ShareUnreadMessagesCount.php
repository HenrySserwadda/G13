<?php
//app/Http/Middleware/SharedUnreadMessagesCount.php
namespace App\Http\Middleware;

use Closure;
use App\Models\ChatMessage;

class ShareUnreadMessagesCount
{
    public function handle($request, Closure $next)
    {
        // dd('ShareUnreadMessagesCount middleware running');
        if (auth()->check()) {
            $unreadCount = ChatMessage::where('receiver_id', auth()->id())
                              ->where('read', false)
                              ->count();
            \Log::info('UnreadCountMiddleware', ['user_id' => auth()->id(), 'unreadCount' => $unreadCount]);
            view()->share('unreadCount', $unreadCount);
        }

        return $next($request);
    }
}