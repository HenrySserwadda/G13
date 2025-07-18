<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\NewRetailerApplied;
use App\Notifications\NewSupplierApplied;

class UserController extends Controller
{
    public function toDashboard(){
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $dashboardUrl = $user->redirectToDashboard();
        return redirect($dashboardUrl);
    }

    public function application(Request $request){
        $user=Auth::user();
        $category = $request->input('categories');
        $user->status='application received';
        $user->pending_category=$category;
        $user->save();
        if($category=='wholesaler'){
            return redirect()->route('insertpdf');
        }
        if($category=='retailer'){
            //Notification::route('mail', 'bluey@hometown.com')->notify(new NewRetailerApplied($user));
            return redirect()
                ->intended($user->redirectToDashboard())
                ->with('success', 'Your retailer application has been submitted and is awaiting approval!')
                ->with('selected_category', $category);
        }
        if($category=='supplier'){
            //Notification::route('mail', 'bluey@hometown.com')->notify(new NewSupplierApplied($user));
            return redirect()
                ->intended($user->redirectToDashboard())
                ->with('success', 'Your supplier application has been submitted and is awaiting approval!')
                ->with('selected_category', $category);;
        }
        return redirect()->back()->with('error', 'Invalid application category selected.');
    }

    public function userProfile(){
        $user=Auth::user();
        return view('user-profile',compact('user'));
    }
}
