<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Table;

class StaffTableController extends Controller
{
    public function index()
    {
        return view('staff.tables.index', [
            'tables' => Table::with('orders')->orderBy('status')->get(),
        ]);
    }

    public function show(Table $table)
    {
        return view('staff.tables.show', [
            'table' => $table->load('orders.items.dish'),
        ]);
    }

    public function occupy(Table $table)
    {
        if ($table->status !== 'available') {
            return back()->with('error', 'La mesa no está disponible');
        }

        $table->update([
            'status' => 'occupied',
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Mesa ocupada');
    }

    public function free(Table $table)
    {
        $table->update([
            'status' => 'available',
            'user_id' => null,
        ]);

        return back()->with('success', 'Mesa liberada');
    }
}
