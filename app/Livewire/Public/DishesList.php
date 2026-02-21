<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Dish;
use App\Models\Category;

class DishesList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $availability = 'available';

    protected $paginationTheme = 'tailwind';

    protected $updatesQueryString = [
        'search',
        'category',
         'availability'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingAvailability()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $dishes = Dish::with(['category', 'allergens'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($this->availability === 'available', function ($query) {
                $query->where('available', true);
            })
            ->when($this->availability === 'unavailable', function ($query) {
                $query->where('available', false);
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->paginate(9);

        return view('livewire.public.dishes-list', [
            'dishes' => $dishes,
            'categories' => Category::all(),
        ]);
    }
}
