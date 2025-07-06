<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Order;

class ReportController extends Controller
{
    public function usersByDate(Request $request){
        /* $query=DB::table('users')->select(DB::raw('name as User_name, created_at as Date'))->orderByDesc('Date');
        $labels=$query->pluck('User_name');
        $data=$query->pluck('Date'); */
        $users=User::all();
        // return view('reports.users',compact('labels','data'));
        return view('reports.users',compact('users'));
    }
    public function ordersPerMonth(Request $request){
        $monthlyOrders=Order::when($request->from,function($query)use($request){
            return $query->whereDate('created_at','>=',$request->from);
        })->when($request->to,function($query)use($request){
            return $query->whereDate('created_at','<=',$request->to);
        })->selectRaw('SUM');
    }

    public function productsOrderedPerMonth(Request $request){
        $pdtsPerMonth=DB::table('order_items')
            ->join('products','order_items.product_id','=','products.id')
            ->join('orders','order_items.order_id','=','orders.id')
            ->select(DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m') as order_month"),'products.name as product_name',DB::raw('SUM(order_items.quantity) as total_quantity_ordered'))//i am confused on which created_at date to use here since i feel like i need to join 3 tables
            ->groupBy('order_month','product_name')
            ->orderByDesc('order_month')
            ->get();
        return response()->json($pdtsPerMonth);
    }
    public function showPdtsPerMonth(){
        return view('reports.sales');
    }

    public function orderedProducts(Request $request){
        $query=DB::table('products')
            ->join('order_items','products.product_id','=','order_items.product_id')
            ->select('',DB::raw('SUM(order_items.quantity) as total_products'))
            ->orderByDesc('order_items.quantity');
    }
    public function productsByPrice(){
        $pdtsByPrice=DB::table('products')
            ->select('name','price')
            ->get();
        return response()->json($pdtsByPrice);
    }
    public function showPdtsByPrice(){
        return view('reports.products_report');
    }
    public function noOfOrders(){
        $noOfOrders=DB::table('orders')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),DB::raw("COUNT(*) as total"))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        return response()->json($noOfOrders);
    }
}

