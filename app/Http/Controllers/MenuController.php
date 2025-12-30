<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Dish;
use App\Models\Offer;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::with('offer')
            ->orderBy('name')
            ->paginate(10);

        return view('menus.index', compact('menus'));
    }

    public function create(): View
    {
        return view('menus.create', [
            'menu' => new Menu(),
            'offers' => Offer::orderBy('name')->get(),
            'dishes' => Dish::orderBy('name')->get(),
        ]);
    }

    public function store(StoreMenuRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $menu = Menu::create($data);

        // Asociar platos al menú si se han seleccionado
        if (!empty($data['dish_ids'] ?? null)) {
            $menu->dishes()->sync($data['dish_ids']);
        }

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menú creado correctamente.');
    }

    public function edit(Menu $menu): View
    {
        return view('menus.edit', [
            'menu' => $menu->load('dishes'),
            'offers' => Offer::orderBy('name')->get(),
            'dishes' => Dish::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateMenuRequest $request, Menu $menu): RedirectResponse
    {
        $data = $request->validated();

        $menu->update($data);

        if (!empty($data['dish_ids'] ?? null)) {
            $menu->dishes()->sync($data['dish_ids']);
        } else {
            $menu->dishes()->detach();
        }

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menú actualizado correctamente.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->dishes()->detach();
        $menu->delete();

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menú eliminado correctamente.');
    }
}
