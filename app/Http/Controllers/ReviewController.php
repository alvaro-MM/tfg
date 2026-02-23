<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with(['user', 'dish', 'drink'])
            ->latest()
            ->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('reviews.create', [
            'dish_id' => $request->dish_id,
            'drink_id' => $request->drink_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(review $review)
    {

        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        return view('reviews.edit', compact('review'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {

        $review->delete();

        return redirect()->route('review.index')->with('success', 'Review eliminado correctamente');
    }
}
