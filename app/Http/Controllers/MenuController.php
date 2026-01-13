<?php

namespace App\Http\Controllers;

use App\Models\Menu;
<<<<<<< HEAD
use App\Models\Offer;
use App\Models\Dish;
=======
use App\Models\Dish;
use App\Models\Offer;
>>>>>>> qr-menu-orders
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
<<<<<<< HEAD
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $menus = Menu::with(['offer', 'dishes', 'tables'])->paginate(15);
=======
    public function index(): View
    {
        $menus = Menu::with('offer')
            ->orderBy('name')
            ->paginate(10);
>>>>>>> qr-menu-orders

        return view('menus.index', compact('menus'));
    }

<<<<<<< HEAD
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $offers = Offer::select('id', 'name')->get();
        $dishes = Dish::select('id', 'name')->get();

        return view('menus.create', compact('offers', 'dishes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request): RedirectResponse
    {
        $menu = Menu::create($request->validated());

        // Attach selected dishes to menu
        if ($request->has('dish_ids')) {
            $menu->dishes()->attach($request->dish_ids);
        }

        return redirect()->route('menus.index')
            ->with('success', 'Menú creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu): View
    {
        $menu->load(['offer', 'dishes', 'tables']);

        return view('menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu): View
    {
        $offers = Offer::select('id', 'name')->get();
        $dishes = Dish::select('id', 'name')->get();

        return view('menus.edit', compact('menu', 'offers', 'dishes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu): RedirectResponse
    {
        $menu->update($request->validated());

        // Sync dishes
        if ($request->has('dish_ids')) {
            $menu->dishes()->sync($request->dish_ids);
        } else {
            $menu->dishes()->detach();
        }

        return redirect()->route('menus.index')
            ->with('success', 'Menú actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        // Check if menu is assigned to tables
        if ($menu->tables()->exists()) {
            return redirect()->route('menus.index')
                ->with('error', 'No se puede eliminar el menú porque está asignado a mesas.');
        }

        $menu->dishes()->detach();
        $menu->delete();

        return redirect()->route('menus.index')
            ->with('success', 'Menú eliminado exitosamente.');
=======
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
>>>>>>> qr-menu-orders
    }
}
