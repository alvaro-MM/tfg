<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;

class StaffOrderController extends Controller
{
    public function index()
    {
        return view('staff.orders.index', [
            'orders' => Order::with('table')
                ->whereIn('status', ['pending', 'preparing'])
                ->latest()
                ->get(),
        ]);
    }
}
