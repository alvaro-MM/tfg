<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\Drink;
use App\Models\Review;
use App\Models\Table;
use App\Models\User;

class OwnerDashboardController extends Controller
{
    public function __invoke()
    {
        return view('owner.dashboard', [
            'dishesCount' => Dish::count(),
            'drinksCount' => Drink::count(),
            'reviewsCount' => Review::count(),
            'averageRating' => round(Review::avg('rating'), 1),

            'latestReviews' => Review::with(['user', 'dish', 'drink'])
                ->latest()
                ->take(5)
                ->get(),

            'topDishes' => Dish::withAvg('reviews', 'rating')
                ->orderByDesc('reviews_avg_rating')
                ->take(5)
                ->get(),

            'topDrinks' => Drink::withAvg('reviews', 'rating')
                ->orderByDesc('reviews_avg_rating')
                ->take(5)
                ->get(),

            'staffUsers' => User::role('staff')->get(),
            'usersWithoutStaff' => User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'staff');
            })->get(),

            'tables' => Table::all(),

        ]);
    }
}
