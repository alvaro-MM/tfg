<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Http\Requests\StoreDishRequest;
use App\Http\Requests\UpdateDishRequest;
use App\Models\Category;
use App\Models\Allergen;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dishes = Dish::with(['category', 'allergens'])->orderBy('name')->paginate(15);

        return view('dishes.index', compact('dishes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $allergens = Allergen::orderBy('name')->get();

        return view('dishes.create', compact('categories', 'allergens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDishRequest $request)
    {
        $data = $request->validated();

        // Handle image upload: store in public disk under 'dishes' directory
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('dishes', 'public');
            $data['image'] = $path;
        }

        // ensure slug exists (migration requires slug)
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        // ensure booleans/defaults
        $data['available'] = $data['available'] ?? false;
        $data['special'] = $data['special'] ?? false;

        $dish = Dish::create($data);

        // Sync multiple allergens if provided (pivot table)
        if (!empty($data['allergen_ids'])) {
            $dish->allergens()->sync($data['allergen_ids']);
        }

        return redirect()->route('dishes.index')->with('success', 'Plato creado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dish $dish)
    {
        return view('dishes.show', compact('dish'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dish $dish)
    {
        $categories = Category::orderBy('name')->get();
        $allergens = Allergen::orderBy('name')->get();

        return view('dishes.edit', compact('dish', 'categories', 'allergens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDishRequest $request, Dish $dish)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('dishes', 'public');
            $data['image'] = $path;
        }

        $data['available'] = $data['available'] ?? false;
        $data['special'] = $data['special'] ?? false;

        $dish->update($data);

        // Sync pivot table for multiple allergens when present
        if (array_key_exists('allergen_ids', $data)) {
            $dish->allergens()->sync($data['allergen_ids'] ?? []);
        }

        return redirect()->route('dishes.show', $dish)->with('success', 'Plato actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dish $dish)
    {
        $dish->allergens()->detach();
        $dish->delete();

        return redirect()->route('dishes.index')->with('success', 'Plato eliminado.');
    }
}
