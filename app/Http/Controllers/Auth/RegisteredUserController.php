<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewCustomerRegistered;
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
            'category'=>['required','in:supplier,retailer,customer,wholesaler,staff']
        ]);
        $requiresApproval=in_array($request->category,['supplier','retailer','wholesaler','staff']);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'category'=>$request->category,
            'status'=>$requiresApproval?'pending':'approved',
            'user_id'=>$requiresApproval?null:User::generateUserId($request->category),
        ]);
        //here is where the logic for sending mail to admin to approve pending users
               
        if($requiresApproval){
            Notification::route('mail','bluey@hometown.com')
                ->notify(new NewUserPendingApproval($user));
            return redirect()->route('login')
                ->with('message','Registration succesful! Please wait for admin approval. You wll be notified by email.');
            
        }
        if(!$requiresApproval){
            $user->status='approved';
            $user->user_id=User::generateUserId('customer');
            $user->notify(new NewCustomerRegistered($user));
        } 
        event(new Registered($user));

//since i have deleted the original dashboard i modify this to lead to different dashboards based on user category
       
        Auth::login($user);

        return redirect($user->redirectToDashboard());
    }
}
