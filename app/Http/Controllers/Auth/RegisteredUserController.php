<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Notifications\NewCustomerRegistered;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Notifications\NewUserPendingApproval;
use App\Notifications\UserApprovedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'date_of_birth' => ['date','before:today'],
        'gender'=>['required']
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'category' => 'customer',
        'gender'=>$request->gender,
        'date_of_birth'=>$request->date_of_birth,
        'user_id'=>null
    ]);

    Auth::login($user);
    event(new Registered($user));
    
    return redirect($user->redirectToDashboard());
}
   /*  public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'date_of_birth' => [
        Rule::requiredIf(function() use ($request) {
            return $request->category === 'customer';
        }),
        'nullable',
        'date',
        'before:today'
    ],
        'category'=>['required','in:supplier,retailer,customer,wholesaler,staff']
    ]);
    
    $requiresApproval = in_array($request->category, ['supplier', 'retailer', 'wholesaler', 'staff']);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'category' => $request->category,
        'status' => $requiresApproval ? 'pending' : 'approved',
        'user_id' => $requiresApproval ? null : User::generateUserId($request->category),
    ]);

    // Handle users requiring approval
    if ($requiresApproval) {
        Notification::route('mail', 'bluey@hometown.com')
            ->notify(new NewUserPendingApproval($user));
        return redirect()->route('login')
            ->with('message', 'Registration successful! Please wait for admin approval. You will be notified by email.');
    }

    // Handle customer registration
    if ($request->category == 'customer') {
        Customer::create([
            'user_id' => $user->user_id,
            'date_of_birth' => $request->date_of_birth
        ]);
        
        $user->notify(new NewCustomerRegistered($user));
        
        // Login the customer before redirecting
        Auth::login($user);
        event(new Registered($user));
        
        return redirect($user->redirectToDashboard())
            ->with('first_time', 'Hello, welcome to the durabag system. You will receive an email containing your user identification number. This is to be used when logging in to the system.');
    }

    // Handle other approved categories (though your current logic suggests these require approval)
    // If any categories fall through here, we should login and redirect
    Auth::login($user);
    event(new Registered($user));
    
    return redirect($user->redirectToDashboard());
} */
}
