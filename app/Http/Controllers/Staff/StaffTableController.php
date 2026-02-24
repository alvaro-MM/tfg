<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Table;

class StaffTableController extends Controller
{
    public function index()
    {
        $tables = Table::withCount([
            'orders as orders_count'
        ])->get();

        return view('staff.tables.index', compact('tables'));
    }

    public function show(Table $table)
    {
        $table->load([
            'orders' => fn ($q) =>
            $q->latest()->with([
                'dishes:id,name,price',
                'drinks:id,name,price',
                'user:id,name'
            ])
        ]);

        return view('staff.tables.show', compact('table'));
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
            'people_count' => $table->capacity,
        ]);

        return back()->with('success', 'Mesa liberada');
    }

    public function reserve(Table $table)
    {
        if ($table->status !== 'available') {
            return back()->with('error', 'La mesa no está disponible.');
        }

        $table->update([
            'status' => 'reserved',
        ]);

        return back()->with('success', 'Mesa reservada correctamente.');
    }

    public function cancelReserve(Table $table)
    {
        if ($table->status !== 'reserved') {
            return back()->with('error', 'La mesa no está reservada.');
        }

        $table->update([
            'status' => 'available',
        ]);

        return back()->with('success', 'Reserva cancelada.');
    }

}
