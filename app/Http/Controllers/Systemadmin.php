<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;   
use App\Notifications\UserApprovedWithNotification;
class Systemadmin extends Controller
{
    public function pendingUsers(){
        $users = User::where("status",'pending')->get();
        return view('dashboard.pending-users',compact('users'));
    }
    public function approve($id){
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->userid=User::generateUserId($user->category);
        $user->save();
        $user->notify(new UserApprovedWithNotification($user));
        return back()->with('success','User approved and notified');
    }
    public function reject($id){
        $user = User::findOrFail($id);  
        $user->status = 'rejected';
        $user->save();
        return back()->with('error','User rejected');
    }
}
