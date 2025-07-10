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

        return view('dashboard.systemadmin', compact('userCount', 'activeUserCount', 'productCount', 'orderCount', 'rawMaterialCount', 'recentActivities', 'recentUsers', 'chartData', 'lowStockProducts', 'completedOrders', 'totalRevenue', 'criticalMaterials', 'recentOrders'));
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