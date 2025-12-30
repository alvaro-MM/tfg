<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Drink;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::all();
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
           $dishes = Dish::all();
           $drinks = Drink::all();

        return view('reviews.create', compact( 'dishes'),compact( 'drinks'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        auth()->user()->reviews()->create($data);

        return redirect()->route('review.index')->with('success', 'Review create successfully');
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
        $dishes = Dish::all();
        $drinks = Drink::all();

        return view('reviews.edit', compact('review', 'dishes', 'drinks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $review->update($data);

        return redirect()->route('review.index')->with('success', 'Review actualizada correctamente');

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
