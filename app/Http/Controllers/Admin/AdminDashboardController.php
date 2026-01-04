<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Table;
use App\Models\Order;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
    
        $today = Carbon::today();

        $totalUsers = User::count();
        $usersToday = User::whereDate('created_at', $today)->count();
        $latestUsers = User::latest()->take(5)->get();

        $totalTables = Table::count();
        $availableTables = Table::where('status', 'available')->count();
        $occupiedTables = Table::where('status', 'occupied')->count();
        $reservedTables = Table::where('status', 'reserved')->count();

        $totalOrders = Order::count();
        $ordersToday = Order::whereDate('created_at', $today)->count();
        $latestOrders = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'usersToday',
            'latestUsers',
            'totalTables',
            'availableTables',
            'occupiedTables',
            'reservedTables',
            'totalOrders',
            'ordersToday',
            'latestOrders'
        ));
    }
}
