<?php

namespace App\Http\Controllers;

use App\Models\Drink;
use App\Models\Category;
use App\Models\Allergen;
use App\Http\Requests\StoreDrinkRequest;
use App\Http\Requests\UpdateDrinkRequest;
use Illuminate\Support\Str;

class DrinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drinks = Drink::with(['category', 'allergens'])
            ->orderBy('name')
            ->paginate(15);

        return view('drinks.index', compact('drinks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $allergens  = Allergen::orderBy('name')->get();

        return view('drinks.create', compact('categories', 'allergens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDrinkRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('drinks', 'public');
        }

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['available'] = $data['available'] ?? false;

        $drink = Drink::create($data);

        if (!empty($data['allergen_ids'])) {
            $drink->allergens()->sync($data['allergen_ids']);
        }

        return redirect()->route('drinks.index')->with('success', 'Bebida creada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Drink $drink)
    {
        return view('drinks.show', compact('drink'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Drink $drink)
    {
        $categories = Category::orderBy('name')->get();
        $allergens  = Allergen::orderBy('name')->get();

        return view('drinks.edit', compact('drink', 'categories', 'allergens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDrinkRequest $request, Drink $drink)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['available'] = $data['available'] ?? false;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('drinks', 'public');
        }

        $drink->update($data);

        if (array_key_exists('allergen_ids', $data)) {
            $drink->allergens()->sync($data['allergen_ids'] ?? []);
        }

        return redirect()->route('drinks.show', $drink)->with('success', 'Bebida actualizada.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Drink $drink)
    {
        $drink->allergens()->detach();
        $drink->delete();

        return redirect()->route('drinks.index')->with('success', 'Bebida eliminada.');
    }
}
