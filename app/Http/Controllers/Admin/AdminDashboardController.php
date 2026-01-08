<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Table;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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
        $latestOrders = Order::with(['user', 'table'])
            ->latest()
            ->take(5)
            ->get();

        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today();

        $chartLabels = [];
        $chartData = [];

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $chartLabels[] = $date->format('d/m');
            $chartData[$date->format('Y-m-d')] = 0;
        }

        $usersPerDay = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        foreach ($usersPerDay as $row) {
            $chartData[$row->date] = $row->total;
        }

        $chartData = array_values($chartData);

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
            'latestOrders',
            'chartLabels',
            'chartData'
        ));
    }
}
