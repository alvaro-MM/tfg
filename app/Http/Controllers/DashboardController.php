<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = Carbon::today();
        $user = auth()->user()->fresh();

        // Redirección según roles
        if ($user->hasRole('admin')) {
            return redirect()->route('admin-dashboard.index');
        }

        if ($user->hasRole('staff')) {
            return redirect()->route('staff-dashboard.index');
        }

        if ($user->hasRole('owner')) {
            return redirect()->route('owner-dashboard.index');
        }

        // --- Lógica para clientes ---
        $totalTables = Table::count();
        $availableTables = Table::where('status', 'available')->count();
        $occupiedTables = Table::where('status', 'occupied')->count();

        $recentOrders = Order::with('table')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $ordersPerDay = auth()->user()
            ->orders()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->limit(7)
            ->get();

        $recentReviews = auth()->user()
            ->reviews()
            ->latest()
            ->limit(3)
            ->get();

        return view('dashboard', [
            'totalTables' => $totalTables,
            'availableTables' => $availableTables,
            'occupiedTables' => $occupiedTables,
            'recentOrders' => $recentOrders,
            'ordersPerDay' => $ordersPerDay,
            'recentReviews' => $recentReviews,
        ]);
    }
}
