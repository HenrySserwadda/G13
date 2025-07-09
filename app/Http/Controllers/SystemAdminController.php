<?php

namespace App\Http\Controllers;

use App\Notifications\AccountDeleted;
use App\Notifications\NewSystemAdmin;
use App\Notifications\UserApprovedWithNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Staff;
use App\Models\Supplier;
use App\Models\Retailer;
use App\Models\Wholesaler;
use App\Models\Systemadmin;
class SystemadminController extends Controller
{
    /**
     * for making a user a system administrator.
     */
    public function makeSystemAdministrator()
    {
        $users = User::where('status','approved')
            ->where('category','!=','systemadmin')
            ->get();
        return view('dashboard.systemadmin.make-system-administrator', compact('users'));
    }
    /**
     * Remove the specified user from the system.
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $user->notify(new AccountDeleted(($user)));
        return redirect()->route('dashboard.systemadmin.all-users')
            ->with('success', 'User deleted successfully.');
    }
    /**
     * Display a listing of all users for the system admin dashboard.
     */
    public function allUsers()
    {
        $users = User::where('status','approved')->get();
        return view('dashboard.systemadmin.all-users', compact('users'));
    }
    public function pendingUsers()
    {
        $users = User::where('status', 'pending')->get();
        return view('dashboard.systemadmin.pending-users', compact('users'));
    }

//to filter users based on categoryphp artisan se
    public function filter(Request $request)
    {
        $category = $request->input('categories', 'all');   
        $query = User::query()->where('status', 'approved');    
        if ($category !== 'all') {
            $query->where('category', $category);
        }    
        $users = $query->get();
        return view('dashboard.systemadmin.all-users', compact('users'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->user_id=User::generateUserId($user->category);
        $user->notify(new UserApprovedWithNotification($user));
        $user->save();

        if($user->category=='staff'){
            Staff::create([
                'user_id'=>$user->user_id
            ]);
        }
        if($user->category=='supplier'){
            Supplier::create([
                'user_id'=>$user->user_id
            ]);
        }
        if($user->category=='wholesaler'){
            Wholesaler::create([
                'user_id'=>$user->user_id
            ]);
        }
        if($user->category=='retailer'){
            Retailer::create([
                'user_id'=>$user->user_id
            ]);
        }
        return redirect()->route('dashboard.systemadmin.pending-users')
            ->with('success', 'User approved successfully.');
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();
        return redirect()->route('dashboard.systemadmin.pending-users')
            ->with('success', 'User rejected successfully.');
    }
    public function makeSystemAdmin($id){
        $user=User::findOrFail($id);
        if($user->status==='approved'){
            $user->category='systemadmin';
            $user->user_id=Systemadmin::generateSystemAdminId($id);
            $user->is_admin=true;
            $user->notify(new NewSystemAdmin($user));
            $user->save();
            Systemadmin::create([
                'user_id'=>$user->user_id
            ]);
            return redirect()->route('dashboard.systemadmin.make-system-administrator')
                ->with('success', 'User made system administrator successfully.');
        } else{
            return redirect()->route('dashboard.systemadmin.make-system-administrator')
                ->with('success','User not eligible');
        }
    
    }

}