<?php

namespace App\Livewire;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Review;
use App\Models\Dish;
use App\Models\Drink;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReviewForm extends Component
{
    use WithFileUploads;

    public $review;

    public $name;
    public $description;
    public $dish_id;
    public $drink_id;
    public $rating = 0;

    public $image;
    public $imagePreview;

    public $dishes;
    public $drinks;

    protected function rules()
    {
        if ($this->review && $this->review->exists) {

            return (new UpdateReviewRequest())->rules();

        }

        return (new StoreReviewRequest())->rules();
    }

    public function mount($review = null)
    {
        $this->dishes = Dish::all();
        $this->drinks = Drink::all();

        if ($review) {
            $this->review = $review;

            $this->name = $review->name;
            $this->description = $review->description;
            $this->dish_id = $review->dish_id;
            $this->drink_id = $review->drink_id;
            $this->rating = $review->rating;

            $this->imagePreview = $review->image
                ? asset('storage/' . $review->image)
                : null;
        }
    }

    public function updatedImage()
    {
        $this->validate(['image' => 'image|max:2048']);

        try {
            $this->imagePreview = $this->image->temporaryUrl();
        } catch (\Exception) {
            $this->addError('image', 'No se pudo cargar la vista previa.');
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        $validatedData['slug'] = Str::slug($this->name);
        $validatedData['user_id'] = auth()->id();

        if ($this->image) {
            if ($this->review && $this->review->image) {
                Storage::disk('public')->delete($this->review->image);
            }

            $validatedData['image'] = $this->image->store('reviews', 'public');
        } else {
            $validatedData['image'] = $this->review->image ?? null;
        }

        Review::updateOrCreate(
            ['id' => optional($this->review)->id],
            $validatedData
        );

        return redirect()->route('review.index')
            ->with('success', $this->review ? 'Review actualizada correctamente' : 'Review creada correctamente');
    }

    public function render()
    {
        return view('livewire.review-form');
    }
}
