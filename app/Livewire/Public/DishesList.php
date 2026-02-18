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

    protected $paginationTheme = 'tailwind';

    protected $updatesQueryString = [
        'search',
        'category'
    ];

    public function updatingSearch()
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
            ->where('available', true)
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
