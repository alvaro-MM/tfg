<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Menu;

class PublicController extends Controller
{
    public function dishes()
    {
        $dishes = Dish::with(['category', 'allergens'])
            ->where('available', true)
            ->get();

        return view('public.dishes', compact('dishes'));
    }

    public function prices()
    {
        $menus = Menu::with('dishes')->get();

        return view('public.prices', compact('menus'));
    }

    public function about()
    {
        return view('public.about');
    }
}
