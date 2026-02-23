<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Drink;
use App\Models\Category;

class DrinksList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $availability = 'available';

    protected $updatesQueryString = [
        'search',
        'category',
        'availability'
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategory() { $this->resetPage(); }
    public function updatingAvailability() { $this->resetPage(); }

    public function render()
    {
        $drinks = Drink::with(['category', 'allergens'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($this->availability === 'available', fn($q) =>
            $q->where('available', true)
            )
            ->when($this->availability === 'unavailable', fn($q) =>
            $q->where('available', false)
            )
            ->when($this->search, fn($q) =>
            $q->where('name', 'like', "%{$this->search}%")
            )
            ->when($this->category, fn($q) =>
            $q->where('category_id', $this->category)
            )
            ->paginate(9);

        return view('livewire.public.drinks-list', [
            'drinks' => $drinks,
            'categories' => Category::all()
        ]);
    }
}
