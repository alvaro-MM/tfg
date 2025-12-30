<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\User;
use App\Models\Menu;
use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::with(['user', 'menu'])->get();

        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.create', [
            'users' => User::all(),
            'menus' => Menu::all(),
        ]);
    }

    public function store(StoreTableRequest $request)
    {
        $data = $request->validated();

        // Si la mesa está libre → user_id debe ser null
        if ($data['status'] === 'available') {
            $data['user_id'] = null;
        }

        // Si la mesa NO está libre → user_id es obligatorio
        if (in_array($data['status'], ['occupied', 'reserved']) && empty($data['user_id'])) {
            return back()
                ->withErrors(['user_id' => 'Debe asignar un usuario si la mesa está ocupada o reservada.'])
                ->withInput();
        }

        $table = Table::create($data);

        // Generar token QR si no existe
        if (!$table->qr_token) {
            $table->generateQrToken();
        }

        return redirect()
            ->route('tables.index')
            ->with('success', 'Mesa creada correctamente.');
    }

    public function show(Table $table)
    {
        return view('tables.show', compact('table'));
    }

    public function edit(Table $table)
    {
        return view('tables.edit', [
            'table' => $table,
            'users' => User::all(),
            'menus' => Menu::all(),
        ]);
    }

    public function update(UpdateTableRequest $request, Table $table)
    {
        $data = $request->validated();

        // Regla de negocio igual que en store
        if ($data['status'] === 'available') {
            $data['user_id'] = null;
        }

        if (in_array($data['status'], ['occupied', 'reserved']) && empty($data['user_id'])) {
            return back()
                ->withErrors(['user_id' => 'Debe asignar un usuario si la mesa está ocupada o reservada.'])
                ->withInput();
        }

        $table->update($data);

        // Asegurar que tiene token QR si ya no lo tenía
        if (!$table->qr_token) {
            $table->generateQrToken();
        }

        return redirect()
            ->route('tables.index')
            ->with('success', 'Mesa actualizada correctamente.');
    }

    public function destroy(Table $table)
    {
        $table->delete();

        return redirect()
            ->route('tables.index')
            ->with('success', 'Mesa eliminada correctamente.');
    }
}
