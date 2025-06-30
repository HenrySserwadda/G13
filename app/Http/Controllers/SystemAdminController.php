<?php

namespace App\Http\Controllers;

use App\Models\Systemadmin;
use Illuminate\Http\Request;
use App\Models\User;   
use App\Notifications\UserApprovedWithNotification;
use App\Notifications\AccountDeleted;
use Illuminate\Notifications\Notification;
use App\Notifications\NewSystemAdmin;
use Illuminate\Support\Facades;
use View;


class SystemAdminController extends Controller
{
    
    public function pendingUsers(){
        $users = User::where("status",'pending')->get();
        return view('dashboard.systemadmin.pending-users',compact('users'));
    }
    public function allUsers(){
        $users = User::where("status",'approved')->get();
        return view('dashboard.systemadmin.all-users',compact('users'));
    }
    
    public function makeSystemAdministrator(){
        $users=User::where('status','approved')->get();
        return view('dashboard.systemadmin.make-system-administrator',compact('users'));
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
    public function delete($id){
        $user = User::findOrFail($id)->where('status','approved');
        $user->delete();
        Notification::route('mail',$user->email)->notify(new AccountDeleted($user));
        return back()->with('success','User deleted from System');
    }

    public function makeSystemAdmin($id){
        $user=User::findOrFail($id)->where('status','approved');
        $user->category='systemadmin';
        $user->userid=Systemadmin::generateSystemAdminId($id); 
        // $user->notify(new NewSystemAdmin($user));
        Notification::route('mail',$user->email)->notify(new NewSystemAdmin($user));
        return back()->with('success', 'New System admin');
    }
}

