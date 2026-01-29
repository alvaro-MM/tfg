<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Table;
use App\Models\Order;
use App\Models\Review;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $usersToday = User::whereDate('created_at', $today)->count();
        $latestUsers = User::latest()
            ->take(5)
            ->get();

        $totalTables = Table::count();

        $availableTables = Table::where('status', 'available')->count();
        $occupiedTables  = Table::where('status', 'occupied')->count();
        $reservedTables  = Table::where('status', 'reserved')->count();

        $tablesOccupationPercent = $totalTables > 0
            ? round(($occupiedTables / $totalTables) * 100)
            : 0;

        $ordersToday = Order::whereDate('created_at', $today)->count();
        $activeOrders = $ordersToday;

        $latestOrders = Order::with(['user', 'table'])
            ->latest()
            ->take(5)
            ->get();

        $ordersPerHour = Order::selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->whereDate('created_at', $today)
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $openHours = array_merge(range(12, 16), range(19, 23));

        $ordersPerHourLabels = [];
        $ordersPerHourData   = [];

        foreach ($openHours as $hour) {
            $ordersPerHourLabels[] = sprintf('%02d:00', $hour);
            $ordersPerHourData[$hour] = 0;
        }

        foreach ($ordersPerHour as $row) {
            if (in_array($row->hour, $openHours)) {
                $ordersPerHourData[$row->hour] = $row->total;
            }
        }

        $ordersPerHourData = array_values($ordersPerHourData);

        $reviewsToday = Review::whereDate('created_at', $today)->count();

        $latestReviewsToday = Review::with(['user', 'dish', 'drink'])
            ->whereDate('created_at', $today)
            ->latest()
            ->take(5)
            ->get();

        $reviewingUsersToday = Review::whereDate('created_at', $today)
            ->distinct('user_id')
            ->count('user_id');

        $topDishesToday = Review::selectRaw('dish_id, COUNT(*) as total')
            ->whereDate('created_at', $today)
            ->groupBy('dish_id')
            ->orderByDesc('total')
            ->with('dish')
            ->take(3)
            ->get();

        $topDrinksToday = Review::selectRaw('drink_id, COUNT(*) as total')
            ->whereDate('created_at', $today)
            ->groupBy('drink_id')
            ->orderByDesc('total')
            ->with('drink')
            ->take(3)
            ->get();

        $alerts = collect();

        if ($tablesOccupationPercent >= 90) {
            $alerts->push('Ocupación de mesas superior al 90%');
        }

        if ($reviewsToday >= 10) {
            $alerts->push('Muchas reseñas publicadas hoy');
        }

        if ($ordersToday >= 20) {
            $alerts->push('Alto volumen de pedidos hoy');
        }

        return view('admin.dashboard', compact(
            'usersToday',
            'latestUsers',

            'totalTables',
            'availableTables',
            'occupiedTables',
            'reservedTables',
            'tablesOccupationPercent',

            'ordersToday',
            'activeOrders',
            'latestOrders',
            'ordersPerHourLabels',
            'ordersPerHourData',

            'reviewsToday',
            'latestReviewsToday',
            'reviewingUsersToday',

            'topDishesToday',
            'topDrinksToday',

            'alerts'
        ));
    }

    public function performance()
    {
        $today = Carbon::today();
        $startDate = Carbon::today()->subDays(6); // últimos 7 días
        $endDate = $today;

        $period = CarbonPeriod::create($startDate, $endDate);

        $chartLabels = [];
        $chartData = [];
        $ordersChartLabels = [];
        $ordersChartData = [];

        foreach ($period as $date) {
            $label = $date->format('d/m');
            $chartLabels[] = $label;
            $ordersChartLabels[] = $label;

            $chartData[$date->format('Y-m-d')] = 0;
            $ordersChartData[$date->format('Y-m-d')] = 0;
        }

        $usersPerDay = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        foreach ($usersPerDay as $row) {
            $chartData[$row->date] = $row->total;
        }

        $ordersPerDay = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        foreach ($ordersPerDay as $row) {
            $ordersChartData[$row->date->format('Y-m-d')] = $row->total;
        }

        $chartData = array_values($chartData);
        $ordersChartData = array_values($ordersChartData);

        $totalUsers = User::count();
        $totalOrders = Order::count();

        return view('admin.performance.index', compact(
            'totalUsers',
            'totalOrders',
            'chartLabels',
            'chartData',
            'ordersChartLabels',
            'ordersChartData'
        ));
    }
}
