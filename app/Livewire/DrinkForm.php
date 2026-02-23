<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Drink;
use App\Models\Category;
use App\Models\Allergen;
use App\Http\Requests\StoreDrinkRequest;
use App\Http\Requests\UpdateDrinkRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DrinkForm extends Component
{
    use WithFileUploads;

    public $drink;

    public $name;
    public $description;
    public $price;
    public $available = true;
    public $category_id;

    public $allergen_ids = []; // seleccionados
    public $allAllergens;   // todos los disponibles
    public $categories;

    public $image;
    public $imagePreview;

    protected function rules()
    {
        if ($this->drink && $this->drink->exists) {

            return (new UpdateDrinkRequest())->rules();
        }

        return (new StoreDrinkRequest())->rules();
    }

    public function mount($drink = null)
    {
        $this->categories = Category::all();
        $this->allAllergens = Allergen::all();

        if ($drink) {
            $this->drink = $drink;

            $this->name = $drink->name;
            $this->description = $drink->description;
            $this->price = $drink->price;
            $this->available = $drink->available;
            $this->category_id = $drink->category_id;
            $this->allergen_ids = $drink->allergens->pluck('id')->toArray();

            $this->imagePreview = $drink->image
                ? asset('storage/' . $drink->image)
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
            if ($this->drink && $this->drink->image) {
                Storage::disk('public')->delete($this->drink->image);
            }

            $validatedData['image'] = $this->image->store('drinks', 'public');
        } else {
            $validatedData['image'] = $this->drink->image ?? null;
        }

        $drink = Drink::updateOrCreate(
            ['id' => optional($this->drink)->id],
            $validatedData
        );

        // Sincronizar alérgenos
        $drink->allergens()->sync($this->allergen_ids);

        return redirect()->route('drinks.index')
            ->with('success', $this->drink ? 'Bebida actualizado correctamente' : 'Bebida creado correctamente');
    }

    public function render()
    {
        return view('livewire.drink-form');
    }
}
