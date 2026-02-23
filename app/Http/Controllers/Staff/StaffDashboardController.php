<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Drink;
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

            'outOfStockDishes' => Dish::with('category')
                ->where('available', false)
                ->get(),

            'outOfStockDrinks' => Drink::with('category')
                ->where('available', false)
                ->get(),

            'allDishes' => Dish::orderBy('name')->get(),
            'allDrinks' => Drink::orderBy('name')->get(),
        ]);
    }

    public function toggleDish(Dish $dish)
    {
        $dish->update([
            'available' => !$dish->available
        ]);

        return back()->with('success', 'Estado del plato actualizado.');
    }

    public function toggleDrink(Drink $drink)
    {
        $drink->update([
            'available' => !$drink->available
        ]);

        return back()->with('success', 'Estado de la bebida actualizado.');
    }
}
