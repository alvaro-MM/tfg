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
            'totalTables' => Table::count(),
            'freeTables' => Table::where('status', 'available')->count(),
            'occupiedTables' => Table::where('status', 'occupied')->count(),
            'reservedTables' => Table::where('status', 'reserved')->count(),
//            'reservedOrders' => Order::where('status', 'reserved')->count(),
            'outOfStockDishes' => Dish::with(['category', 'allergens'])->where('available', false)->get()

        ]);
    }
}
