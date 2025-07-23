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
            ->where('category','!=','systemadmin')
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
        $users = User::all();
        return view('dashboard.systemadmin.all-users', compact('users'));
    }
    public function pendingRetailers()
    {
        $users = User::where('status', 'application received')
            ->where('pending_category','retailer')
            ->get();
        return view('dashboard.systemadmin.pending-retailers', compact('users'));
    }
    public function pendingWholesalers()
    {
        $users = User::where('status', 'application received')
            ->where('pending_category','wholesaler')
            ->get();
        return view('dashboard.systemadmin.pending-wholesalers', compact('users'));
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

        public function handleAdminAction(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $action = $request->input('action');
        $category = $user->pending_category;

        if ($action === 'approve') {
            $user->status = 'application approved';
            $user->category = $category;
            $user->pending_category = null;
            $user->user_id = User::generateUserId($category);
            //$user->notify(new UserApprovedWithNotification($user));
            $user->save();

            if ($category === 'retailer') {
                Retailer::create(['user_id' => $user->user_id]);
            } elseif ($category === 'wholesaler') {
                Wholesaler::create(['user_id' => $user->user_id]);
            } elseif ($category === 'supplier') {
                Supplier::create(['user_id' => $user->user_id]);
            }

            return back()->with('success', ucfirst($category) . ' approved successfully.');
        }

        if ($action === 'reject') {
            $user->status = 'application rejected';
            $user->pending_category = null;
            $user->save();
            $user->notify(new UserRejectedWithNotification($user));

            return back()->with('success', ucfirst($category) . ' application rejected.');
        }

        return back()->with('error', 'Invalid action.');
    }

    public function makeSystemAdmin($id){
        $user=User::findOrFail($id);
        $user->category='systemadmin';
        $user->pending_category=null;
        $user->status='no application';
        $user->user_id=Systemadmin::generateSystemAdminId($id);
        $user->is_admin=true;
        //$user->notify(new NewSystemAdmin($user));
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
        $user->pending_category=null;
        $user->status='no application';
        $user->user_id=User::generateUserId($user->category);
        //$user->notify(new NewStaffMember($user));
        $user->save();
        Staff::create([
            'user_id'=>$user->user_id
        ]);
        return redirect()->route('dashboard.systemadmin.make-staff-member')
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

        // User registration trend data for chart
        $userRegData = \App\Models\User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');
        
        return view('dashboard.systemadmin', compact('userCount', 'activeUserCount', 'productCount', 'orderCount', 'rawMaterialCount', 'recentActivities', 'recentUsers', 'chartData', 'lowStockProducts', 'completedOrders', 'totalRevenue', 'criticalMaterials', 'recentOrders', 'products', 'userRegData'));
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

    /**
     * Show user segments for admin/staff dashboard
     */
    public function userSegments()
    {
        $user = auth()->user();
        if (!$user || !in_array(strtolower($user->category), ['systemadmin', 'admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $users = \App\Models\User::with('orders')->get();
        $segments = [
            'By Gender' => [],
            'High Frequency Buyers' => [],
            'High Budget Users' => [],
            'Dormant Users' => [],
            'By Preferred Style' => [],
            'By Preferred Color' => [],
        ];
        $now = now();
        foreach ($users as $user) {
            $orders = $user->orders()->with('items.product')->get();
            $orderCount = $orders->count();
            $totalSpent = $orders->sum('total');
            $avgOrderValue = $orderCount > 0 ? $totalSpent / $orderCount : 0;
            $lastOrder = $orders->sortByDesc('created_at')->first();
            $lastOrderDate = $lastOrder ? $lastOrder->created_at : null;
            // Gender
            $gender = $user->gender ?? 'Unknown';
            $segments['By Gender'][$gender][] = $user;
            // High frequency
            if ($orderCount >= 5) {
                $segments['High Frequency Buyers'][] = $user;
            }
            // High budget
            if ($avgOrderValue >= 200000) {
                $segments['High Budget Users'][] = $user;
            }
            // Dormant
            if ($lastOrderDate && $now->diffInDays($lastOrderDate) > 90) {
                $segments['Dormant Users'][] = $user;
            }
            // Preferred style/color
            $styleCounts = [];
            $colorCounts = [];
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        if ($product->ml_style) {
                            $styleCounts[$product->ml_style] = ($styleCounts[$product->ml_style] ?? 0) + 1;
                        }
                        if ($product->ml_color) {
                            $colorCounts[$product->ml_color] = ($colorCounts[$product->ml_color] ?? 0) + 1;
                        }
                    }
                }
            }
            if (!empty($styleCounts)) {
                arsort($styleCounts);
                $topStyle = array_key_first($styleCounts);
                $segments['By Preferred Style'][$topStyle][] = $user;
            }
            if (!empty($colorCounts)) {
                arsort($colorCounts);
                $topColor = array_key_first($colorCounts);
                $segments['By Preferred Color'][$topColor][] = $user;
            }
        }
        $genderCounts = $users->groupBy('gender')->map->count();
        $segmentCounts = collect($segments)->map(function($group) {
            if (is_array($group)) {
                return collect($group)->flatten(1)->count();
            }
            return is_array($group) ? count($group) : 0;
        });

        // Orders over time (by day, by category)
        $ordersByDay = $users->flatMap->orders
            ->filter()
            ->groupBy(function($order) { return $order->created_at ? $order->created_at->format('Y-m-d') : 'Unknown'; })
            ->map->count();
        $ordersByDayCategory = $users->flatMap(function($user) {
            return $user->orders->map(function($order) use ($user) {
                return [
                    'day' => $order->created_at ? $order->created_at->format('Y-m-d') : 'Unknown',
                    'category' => $user->category ?? 'Unknown',
                ];
            });
        })->groupBy('category')->map(function($orders) {
            return collect($orders)->groupBy('day')->map->count();
        });

        // Revenue by segment (by day range)
        $revenueBySegmentDay = collect($segments)->map(function($group, $segment) {
            $users = is_array($group) ? collect($group)->flatten(1) : collect([]);
            $byDay = collect();
            foreach ($users as $user) {
                foreach ($user->orders as $order) {
                    $day = $order->created_at ? $order->created_at->format('Y-m-d') : 'Unknown';
                    $byDay[$day] = ($byDay[$day] ?? 0) + $order->total;
                }
            }
            return $byDay;
        });

        // Top styles/colors purchased (by gender)
        $stylesByGender = $users->groupBy('gender')->map(function($users) {
            $styles = [];
            foreach ($users as $user) {
                foreach ($user->orders as $order) {
                    foreach ($order->items as $item) {
                        $product = $item->product;
                        if ($product && $product->ml_style) {
                            $styles[$product->ml_style] = ($styles[$product->ml_style] ?? 0) + 1;
                        }
                    }
                }
            }
            arsort($styles);
            return $styles;
        });
        $colorsByGender = $users->groupBy('gender')->map(function($users) {
            $colors = [];
            foreach ($users as $user) {
                foreach ($user->orders as $order) {
                    foreach ($order->items as $item) {
                        $product = $item->product;
                        if ($product && $product->ml_color) {
                            $colors[$product->ml_color] = ($colors[$product->ml_color] ?? 0) + 1;
                        }
                    }
                }
            }
            arsort($colors);
            return $colors;
        });

        // Active vs. dormant users (by category)
        $now = now();
        $activeDormantByCategory = $users->groupBy('category')->map(function($users) use ($now) {
            $active = 0; $dormant = 0;
            foreach ($users as $user) {
                $lastOrder = $user->orders->sortByDesc('created_at')->first();
                if ($lastOrder && $now->diffInDays($lastOrder->created_at) <= 90) {
                    $active++;
                } else {
                    $dormant++;
                }
            }
            return ['active' => $active, 'dormant' => $dormant];
        });

        // Per-user daily data for orders and amount spent
        $allUsers = $users->sortBy('name')->values();
        $ordersByDayUser = [];
        $amountByDayUser = [];
        foreach ($allUsers as $user) {
            $ordersByDayUser[$user->id] = $user->orders->groupBy(function($order) {
                return $order->created_at ? $order->created_at->format('Y-m-d') : 'Unknown';
            })->map->count();
            $amountByDayUser[$user->id] = $user->orders->groupBy(function($order) {
                return $order->created_at ? $order->created_at->format('Y-m-d') : 'Unknown';
            })->map(function($orders) {
                return $orders->sum('total');
            });
        }
        // Also prepare 'all' for all users combined
        $ordersByDayUser['all'] = $ordersByDay;
        $amountByDayUser['all'] = $users->flatMap->orders
            ->filter()
            ->groupBy(function($order) { return $order->created_at ? $order->created_at->format('Y-m-d') : 'Unknown'; })
            ->map(function($orders) { return $orders->sum('total'); });

        return view('dashboard.user-segments', compact(
            'segments', 'genderCounts', 'segmentCounts',
            'ordersByDay', 'ordersByDayCategory', 'revenueBySegmentDay',
            'stylesByGender', 'colorsByGender', 'activeDormantByCategory',
            'allUsers', 'ordersByDayUser', 'amountByDayUser'
        ));
    }
}