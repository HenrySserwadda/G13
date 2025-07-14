<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
        } catch (ValidationException $redirection) {

            if (isset($redirection->validator->errors()->messages()['redirect'][0])) {
                return redirect($redirection->validator->errors()->messages()['redirect'][0]);
            }
            throw $redirection; 
        }


        $user = Auth::user();
        if ($user) { 
            return redirect()->intended($user->redirectToDashboard());
        }

        return redirect()->route('login')->withErrors(['email' => __('Something went wrong during login.')]);
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showUserIdForm(): View|RedirectResponse
    {
        if (!Session::has('temp_user_id')) {
            return redirect()->route('login')->with('error', 'Please log in with your email and password first.');
        }
        $user = User::find(Session::get('temp_user_id'));
        if (!$user || $user->category === 'customer') {
            Session::forget('temp_user_id');
            Session::forget('remember_me');
            return redirect()->route('login')->with('error', 'Invalid session for user ID verification.');
        }

        return view('auth.user_id');
    }

    public function verifyUserId(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'string'],
        ]);

        if (!Session::has('temp_user_id')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }
        $user = User::find(Session::get('temp_user_id'));
        if (!$user) {
            Session::forget('temp_user_id');
            Session::forget('remember_me');
            return redirect()->route('login')->with('error', 'User not found. Please log in again.');
        }
        if ($user->category === 'customer') {
            Auth::login($user, Session::get('remember_me', false));
            Session::forget('temp_user_id');
            Session::forget('remember_me');
            return redirect()->intended($user->redirectToDashboard());
        }

        if ($request->user_id !== $user->user_id) {
            Session::forget('temp_user_id'); 
            Session::forget('remember_me');
            throw ValidationException::withMessages(['user_id' => __('The User ID you provided is incorrect.')]);
        }
        Auth::login($user, Session::get('remember_me', false));
        Session::forget('temp_user_id');
        Session::forget('remember_me');

        return redirect()->intended($user->redirectToDashboard());
    }
}

