<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Dish;
use App\Models\Category;
use App\Models\Allergen;
use App\Http\Requests\StoreDishRequest;
use App\Http\Requests\UpdateDishRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DishForm extends Component
{
    use WithFileUploads;

    public $dish;

    public $name;
    public $description;
    public $ingredients;
    public $price;
    public $available = true;
    public $special = false;
    public $category_id;

    public $allergens = []; // seleccionados
    public $allAllergens;   // todos los disponibles
    public $categories;

    public $image;
    public $imagePreview;

    protected function rules()
    {
        if ($this->dish && $this->dish->exists) {

            return (new UpdateDishRequest())->rules();
        }

        return (new StoreDishRequest())->rules();
    }

    public function mount($dish = null)
    {
        $this->categories = Category::all();
        $this->allAllergens = Allergen::all();

        if ($dish) {
            $this->dish = $dish;

            $this->name = $dish->name;
            $this->description = $dish->description;
            $this->ingredients = $dish->ingredients;
            $this->price = $dish->price;
            $this->available = $dish->available;
            $this->special = $dish->special;
            $this->category_id = $dish->category_id;
            $this->allergens = $dish->allergens->pluck('id')->toArray();

            $this->imagePreview = $dish->image
                ? asset('storage/' . $dish->image)
                : null;
        }
    }

    public function updatedImage()
    {
        $this->validate(['image' => 'image|max:2048']);
        $this->imagePreview = $this->image->temporaryUrl();
    }

    public function save()
    {
        $validatedData = $this->validate();

        $validatedData['slug'] = Str::slug($this->name);

        if ($this->image) {
            if ($this->dish && $this->dish->image) {
                Storage::disk('public')->delete($this->dish->image);
            }

            $validatedData['image'] = $this->image->store('dishes', 'public');
        } else {
            $validatedData['image'] = $this->dish->image ?? null;
        }

        $dish = Dish::updateOrCreate(
            ['id' => optional($this->dish)->id],
            $validatedData
        );

        // Sincronizar alérgenos
        $dish->allergens()->sync($this->allergens);

        return redirect()->route('dishes.index')
            ->with('success', $this->dish ? 'Plato actualizado correctamente' : 'Plato creado correctamente');
    }

    public function render()
    {
        return view('livewire.dish-form');
    }
}
