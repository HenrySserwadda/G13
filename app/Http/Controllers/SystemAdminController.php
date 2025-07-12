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

    public function dashboard()
    {
        $userCount = \App\Models\User::count();
        $activeUserCount = \App\Models\User::where('status', 'approved')->count();
        $productCount = \App\Models\Product::count();
        $orderCount = \App\Models\Order::count();
        $rawMaterialCount = \App\Models\RawMaterial::count();

        // Add low stock products (quantity <= 10)
        $lowStockProducts = \App\Models\Product::where('quantity', '<=', 10)->get();

        // Add completed orders
        $completedOrders = \App\Models\Order::where('status', 'completed')->get();

        // Add total revenue (sum of 'total' for completed orders)
        $totalRevenue = \App\Models\Order::where('status', 'completed')->sum('total');

        // Add critical raw materials (quantity <= 10)
        $criticalMaterials = \App\Models\RawMaterial::where('quantity', '<=', 10)->get();

        // Add recent orders (last 5)
        $recentOrders = \App\Models\Order::orderBy('created_at', 'desc')->take(5)->get();

        // Recent activities: last 5 users, orders, and products (merged and sorted by created_at desc)
        $recentUsers = \App\Models\User::orderBy('created_at', 'desc')->paginate(5);
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

        // Add dynamic chart data
        $python = config('ml.python_path', 'python');
        $script = config('ml.scripts_path', base_path('ml-scripts')) . '/custom_chart.py';
        $output = [];
        $return_var = 0;
        $cmd = "$python $script --chart_type bar --x_axis Month --y_axis Sales --json";
        exec($cmd, $output, $return_var);
        $chartData = json_decode(implode('', $output), true);

        // Fetch products for the dashboard
        $products = \App\Models\Product::latest()->paginate(12);
        
        return view('dashboard.systemadmin', compact('userCount', 'activeUserCount', 'productCount', 'orderCount', 'rawMaterialCount', 'recentActivities', 'recentUsers', 'chartData', 'lowStockProducts', 'completedOrders', 'totalRevenue', 'criticalMaterials', 'recentOrders', 'products'));
    }

    public function activityLog()
    {
        // For demonstration, use the same activity structure as dashboard, but paginated
        $userActivities = collect();
        $recentUsers = \App\Models\User::orderBy('created_at', 'desc')->paginate(10);
        foreach ($recentUsers as $user) {
            $userActivities->push((object)[
                'description' => 'New user registered: ' . $user->name,
                'icon' => 'user-plus',
                'created_at' => $user->created_at,
                'causer' => $user,
            ]);
        }
        $recentOrders = \App\Models\Order::orderBy('created_at', 'desc')->paginate(10);
        foreach ($recentOrders as $order) {
            $userActivities->push((object)[
                'description' => 'New order placed: #' . $order->id,
                'icon' => 'shopping-cart',
                'created_at' => $order->created_at,
                'causer' => null,
            ]);
        }
        $recentProducts = \App\Models\Product::orderBy('created_at', 'desc')->paginate(10);
        foreach ($recentProducts as $product) {
            $userActivities->push((object)[
                'description' => 'New product added: ' . $product->name,
                'icon' => 'box-open',
                'created_at' => $product->created_at,
                'causer' => null,
            ]);
        }
        $allActivities = $userActivities->sortByDesc('created_at')->values();
        // Paginate manually since we merged collections
        $perPage = 15;
        $currentPage = request()->input('page', 1);
        $pagedActivities = $allActivities->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $activities = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedActivities,
            $allActivities->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return view('dashboard.activity-log', compact('activities'));
    }
}