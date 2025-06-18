// app/Http/Middleware/ShareUnreadMessagesCount.php
namespace App\Http\Middleware;

use Closure;
use App\Models\Chat;

class ShareUnreadMessagesCount
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $unreadCount = Chat::where('receiver_id', auth()->id())
                              ->where('read', false)
                              ->count();
            
            view()->share('unreadCount', $unreadCount);
        }

        return $next($request);
    }
}