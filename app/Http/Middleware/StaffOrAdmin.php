<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user && in_array($user->category, ['systemadmin', 'staff'])) {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }
} 