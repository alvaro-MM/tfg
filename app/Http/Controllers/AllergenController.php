<?php

namespace App\Http\Controllers;

use App\Models\Allergen;
use Illuminate\Http\Request;
use App\Http\Requests\StoreallergenRequest;
use App\Http\Requests\UpdateallergenRequest;

class AllergenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allergens = Allergen::all();
        return view('allergens.index', compact('allergens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('allergens.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAllergenRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('allergens', 'public');
            $data['image'] = '/storage/' . $path;
        }

        Allergen::create($data);

        return redirect()->route('allergens.index')->with('success', 'Alérgeno creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(allergen $allergen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Allergen $allergen)
    {
        return view('allergens.edit', compact('allergen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAllergenRequest $request, Allergen $allergen)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($allergen->image) {
                $oldPath = str_replace('/storage/', '', $allergen->image);
                \Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('allergens', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $allergen->update($data);

        return redirect()->route('allergens.index')->with('success', 'Alérgeno actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Allergen $allergen)
    {
        if ($allergen->image) {
            $oldPath = str_replace('/storage/', '', $allergen->image);
            \Storage::disk('public')->delete($oldPath);
        }

        $allergen->delete();

        return redirect()->route('allergens.index')->with('success', 'Alérgeno eliminado correctamente');
    }
}
