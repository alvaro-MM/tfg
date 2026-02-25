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
     * Remove the specified resource from storage.
     */
    public function destroy(Dish $dish)
    {
        $dish->allergens()->detach();
        $dish->delete();

        return redirect()->route('dishes.index')->with('success', 'Plato eliminado.');
    }
}
