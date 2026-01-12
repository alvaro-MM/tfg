<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Order;
use App\Models\Dish;

class StaffDashboardController extends Controller
{
    public function index()
    {
        return view('staff.dashboard', [
            'freeTables' => Table::where('status', 'available')->count(),
            'occupiedTables' => Table::where('status', 'occupied')->count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'outOfStockDishes' => Dish::where('stock', '<=', 0)->get(),
        ]);
    }
}
