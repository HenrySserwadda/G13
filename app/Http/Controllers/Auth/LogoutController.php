<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Session; 

class LogoutController extends Controller
{
    /**
     * Log the user out of the application.
     * This uses the __invoke method, making it a single-action controller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        // 1. Log the current user out of all guards
        Auth::logout();

        // 2. Invalidate the current session
        // This clears all session data for the user, enhancing security.
        $request->session()->invalidate();

        // 3. Regenerate the CSRF token
        // This is important after session invalidation to prevent session fixation attacks.
        $request->session()->regenerateToken();

        // 4. Redirect the user to a desired location after logout
        // You can redirect to the home page ('/') or the login page (route('login'))
        return redirect('/');
        // return redirect()->route('login'); // Alternative: redirect to login page
    }
}

