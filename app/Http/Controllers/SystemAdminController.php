<?php

namespace App\Http\Controllers;

use App\Notifications\AccountDeleted;
use App\Notifications\NewSystemAdmin;
use App\Notifications\UserApprovedWithNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Systemadmin;
class SystemadminController extends Controller
{
    /**
     * Show the form for making a user a system administrator.
     */
    public function makeSystemAdministrator()
    {
        $users = User::where('status','approved')
            ->where('category','!=','systemadmin')
            ->get();
        return view('dashboard.systemadmin.make-system-administrator', compact('users'));
    }
    /**
     * Remove the specified user from storage.
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

    public function approve($id)
    {
        // Example: Approve a user by ID
        $user = User::findOrFail($id);
        $user->status = 'approved';

        $user->user_id=User::generateUserId($user->category);

        
        $user->notify(new UserApprovedWithNotification($user));
        $user->save();
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
            return redirect()->route('dashboard.systemadmin.make-system-administrator')
                ->with('success', 'User made system administrator successfully.');
        } else{
            return redirect()->route('dashboard.systemadmin.make-system-administrator')
                ->with('success','User not eligible');
        }
    
    }

    public function dashboard()
    {
        $userCount = \App\Models\User::count();
        $productCount = \App\Models\Product::count();
        $orderCount = \App\Models\Order::count();
        $rawMaterialCount = \App\Models\RawMaterial::count();

        // Recent activities: last 5 users, orders, and products (merged and sorted by created_at desc)
        $recentUsers = \App\Models\User::orderBy('created_at', 'desc')->take(5)->get();
        $recentOrders = \App\Models\Order::orderBy('created_at', 'desc')->take(5)->get();
        $recentProducts = \App\Models\Product::orderBy('created_at', 'desc')->take(5)->get();

        $activities = collect();
        foreach ($recentUsers as $user) {
            $activities->push((object)[
                'description' => 'New user registered: ' . $user->name,
                'icon' => 'user-plus',
                'created_at' => $user->created_at,
            ]);
        }
        foreach ($recentOrders as $order) {
            $activities->push((object)[
                'description' => 'New order placed: #' . $order->id,
                'icon' => 'shopping-cart',
                'created_at' => $order->created_at,
            ]);
        }
        foreach ($recentProducts as $product) {
            $activities->push((object)[
                'description' => 'New product added: ' . $product->name,
                'icon' => 'box-open',
                'created_at' => $product->created_at,
            ]);
        }
        $recentActivities = $activities->sortByDesc('created_at')->take(5);

        return view('dashboard.systemadmin', compact('userCount', 'productCount', 'orderCount', 'rawMaterialCount', 'recentActivities', 'recentUsers'));
    }

}