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
use App\Notifications\UserRejectedWithNotification;
use App\Notifications\NewStaffMember;
class SystemadminController extends Controller
{
    /**
     * for making a user a system administrator.
     */
    public function makeSystemAdministrator()
    {
        $users = User::where('category','!=','systemadmin')
            ->get();
        return view('dashboard.systemadmin.make-system-administrator', compact('users'));
    }
    public function makeStaffMember()
    {
        $users = User::where('category','!=','staff')
            ->get();
        return view('dashboard.systemadmin.make-staff', compact('users'));
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
    public function pendingRetailers()
    {
        $users = User::where('status', 'application received')
            ->where('pending_category','retailer')
            ->get();
        return view('dashboard.systemadmin.pending-retailers', compact('users'));
    }
    public function pendingSuppliers()
    {
        $users = User::where('status', 'application received')
            ->where('pending_category','supplier')
            ->get();
        return view('dashboard.systemadmin.pending-suppliers', compact('users'));
    }

//to filter users based on category
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

    public function approveRetailers($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'application approved';
        $user->category='retailer';
        $user->pending_category=null;
        $user->user_id=User::generateUserId($user->category);
        $user->notify(new UserApprovedWithNotification($user));
        $user->save();
        Retailer::create([
                'user_id'=>$user->user_id
            ]);
        return redirect()->route('dashboard.systemadmin.pending-retailers')
            ->with('success', 'User approved successfully.');
    }

    public function rejectRetailers($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'application rejected';
        $user->pending_category=null;
        $user->save();
        $user->notify(new UserRejectedWithNotification($user));
        return redirect()->route('dashboard.systemadmin.pending-suppliers')
            ->with('error', 'Supplier application rejected.');
    }
    public function approveSuppliers($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'application approved';
        $user->category='supplier';
        $user->pending_category=null;
        $user->user_id=User::generateUserId($user->category);
        $user->notify(new UserApprovedWithNotification($user));
        $user->save();
        Supplier::create([
                'user_id'=>$user->user_id
            ]);
        return redirect()->route('dashboard.systemadmin.pending-suppliers')
            ->with('success', 'User approved successfully.');
    }

    public function rejectSuppliers($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'application rejected';
        $user->pending_category=null;
        $user->save();
        $user->notify(new UserRejectedWithNotification($user));
        return redirect()->route('dashboard.systemadmin.pending-suppliers')
            ->with('error', 'Supplier application rejected.');
    }
    /* public function approve($id)
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
    } */

    public function makeSystemAdmin($id){
        $user=User::findOrFail($id);
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
    }
    public function makeStaff($id){
        $user=User::findOrFail($id);
        $user->category='staff';
        $user->user_id=User::generateUserId($id);
        $user->notify(new NewStaffMember($user));
        $user->save();
        Staff::create([
            'user_id'=>$user->user_id
        ]);
        return redirect()->route('dashboard.systemadmin.make-staff')
            ->with('success', 'User made staff successfully.');    
    }

}