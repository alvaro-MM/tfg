<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Table;
use App\Models\Order;
use App\Models\Review;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
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

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $ordersPerHour = Order::selectRaw("CAST(strftime('%H', created_at) AS INTEGER) as hour, COUNT(*) as total")
                ->whereDate('created_at', now())
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();
        } else {
            $ordersPerHour = Order::selectRaw("HOUR(created_at) as hour, COUNT(*) as total")
                ->whereDate('created_at', now())
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();
        }

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

    public function billing()
    {
        $today = Carbon::today();

        $stats = [
            'today' => Invoice::whereDate('date', $today)->sum('total'),

            'week' => Invoice::whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->sum('total'),

            'month' => Invoice::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('total'),

            'year' => Invoice::whereYear('date', now()->year)
                ->sum('total'),
        ];

        $dailyInvoices = Invoice::select(
            DB::raw('DATE(date) as day'),
            DB::raw('SUM(total) as total')
        )
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $chartLabels = [];
        $chartData   = [];

        foreach ($dailyInvoices as $row) {
            $chartLabels[] = Carbon::parse($row->day)->format('d/m');
            $chartData[]   = (float) $row->total;
        }

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $monthlyInvoices = Invoice::selectRaw("CAST(strftime('%m', date) AS INTEGER) as month, SUM(total) as total")
                ->whereYear('date', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } else {
            $monthlyInvoices = Invoice::selectRaw("MONTH(date) as month, SUM(total) as total")
                ->whereYear('date', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        $chartLabels = [
            'Ene',
            'Feb',
            'Mar',
            'Abr',
            'May',
            'Jun',
            'Jul',
            'Ago',
            'Sep',
            'Oct',
            'Nov',
            'Dic'
        ];

        $chartData = array_fill(0, 12, 0);

        foreach ($monthlyInvoices as $row) {
            $index = $row->month - 1;
            $chartData[$index] = (float) $row->total;
        }

        return view('admin.billing.index', compact(
            'stats',
            'chartLabels',
            'chartData'
        ));
    }

    public function performance()
    {
        $today = Carbon::today();
        $startDate = Carbon::today()->subDays(6);
        $endDate = $today;

        $period = CarbonPeriod::create($startDate, $endDate);

        $chartLabels = [];
        $chartData = [];
        $ordersChartLabels = [];
        $ordersChartData = [];

        $lastWeekStart = Carbon::today()->subDays(13);
        $lastWeekEnd   = Carbon::today()->subDays(7);
        $thisWeekStart = Carbon::today()->subDays(6);
        $thisWeekEnd   = Carbon::today();

        $usersThisWeek = User::whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])->count();
        $usersLastWeek = User::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();
        $userGrowth = $usersLastWeek > 0 ? round((($usersThisWeek - $usersLastWeek) / $usersLastWeek) * 100, 2) : null;

        $ordersThisWeek = Order::whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])->count();
        $ordersLastWeek = Order::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();
        $ordersGrowth = $ordersLastWeek > 0 ? round((($ordersThisWeek - $ordersLastWeek) / $ordersLastWeek) * 100, 2) : null;

        $reviewsThisWeek = Review::whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])->count();
        $reviewsLastWeek = Review::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();
        $reviewsGrowth = $reviewsLastWeek > 0 ? round((($reviewsThisWeek - $reviewsLastWeek) / $reviewsLastWeek) * 100, 2) : null;

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
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();

        foreach ($ordersPerDay as $row) {
            $ordersChartData[$row->date->format('Y-m-d')] = $row->total;
        }

        $chartData = array_values($chartData);
        $ordersChartData = array_values($ordersChartData);

        $topDishes = Review::selectRaw('dish_id, COUNT(*) as total')
            ->whereDate('created_at', $today)
            ->whereNotNull('dish_id')
            ->groupBy('dish_id')
            ->with('dish')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $topDrinks = Review::selectRaw('drink_id, COUNT(*) as total')
            ->whereDate('created_at', $today)
            ->whereNotNull('drink_id')
            ->groupBy('drink_id')
            ->with('drink')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalReviews = Review::count();

        return view('admin.performance.index', compact(
            'totalUsers',
            'totalOrders',
            'totalReviews',
            'chartLabels',
            'chartData',
            'ordersChartLabels',
            'ordersChartData',
            'topDishes',
            'topDrinks',
            'userGrowth',
            'ordersGrowth',
            'reviewsGrowth'
        ));
    }
}
