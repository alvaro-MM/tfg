<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Offer;
use App\Models\Dish;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $menus = Menu::with(['offer', 'dishes', 'tables'])->paginate(15);

        return view('menus.index', compact('menus'));
    }

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
    }
}
