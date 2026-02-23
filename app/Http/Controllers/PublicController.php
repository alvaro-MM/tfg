<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Menu;

class PublicController extends Controller
{
    public function dishes()
    {
        return view('public.dishes');
    }
    public function drinks()
    {
        return view('public.drinks');
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
