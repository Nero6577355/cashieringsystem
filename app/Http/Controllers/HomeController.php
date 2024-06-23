<?php

namespace App\Http\Controllers;

use App\Models\AddCashier;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Food;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
{
    // Get the logged-in user
    $user = Auth::user();
    $userRole = $user->roles;
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();
    $today = Carbon::today()->format('Y-m-d');
    $now = Carbon::now();

    // Determine if the user is a manager
    $isManager = $userRole === 'manager';

    // Weekly orders count
    $weeklyOrdersQuery = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                              ->where('status', 'Paid');
    if (!$isManager) {
        $weeklyOrdersQuery->where('cashier_id', $user->id);
    }
    $weeklyOrders = $weeklyOrdersQuery->sum('total_quantity');

    // Daily orders count
    $dailyOrdersQuery = Order::whereDate('created_at', $today)
                             ->where('status', 'Paid');
    if (!$isManager) {
        $dailyOrdersQuery->where('cashier_id', $user->id);
    }

    $dailyOrders = $dailyOrdersQuery->sum('total_quantity');
    // Daily sales sum
    $dailySalesQuery = Order::whereDate('created_at', $today)
                            ->where('status', 'Paid');
    if (!$isManager) {
        $dailySalesQuery->where('cashier_id', $user->id);
    }
    $dailySales = $dailySalesQuery->sum('total_price');

    // Weekly sales sum
    $weeklySalesQuery = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                             ->where('status', 'Paid');
    if (!$isManager) {
        $weeklySalesQuery->where('cashier_id', $user->id);
    }
    $weeklySales = $weeklySalesQuery->sum('total_price');

    // Total orders count
    $totalOrdersQuery = Order::where('status', 'Paid');
    if (!$isManager) {
        $totalOrdersQuery->where('cashier_id', $user->id);
    }
    $totalOrders = $totalOrdersQuery->count();

    // Weekly sales data
    $weeklySalesData = [];
    for ($i = 0; $i < 7; $i++) {
        $startOfDay = $startOfWeek->copy()->addDays($i)->startOfDay();
        $endOfDay = $startOfWeek->copy()->addDays($i)->endOfDay();

        $dailySalesQuery = Order::whereBetween('created_at', [$startOfDay, $endOfDay])
                                ->where('status', 'Paid');
        if (!$isManager) {
            $dailySalesQuery->where('cashier_id', $user->id);
        }
        $totalSales = $dailySalesQuery->sum('total_price');
        $weeklySalesData[] = $totalSales;
    }

    // Other counts
    $categoriesCount = FoodCategory::count();
    $foods = Food::count();
    $addcashier = AddCashier::count();

    return view('home', compact(
        'totalOrders', 
        'weeklySalesData', 
        'dailyOrders', 
        'weeklyOrders', 
        'dailySales', 
        'weeklySales', 
        'addcashier', 
        'foods', 
        'categoriesCount'
    ));
}

    

}
