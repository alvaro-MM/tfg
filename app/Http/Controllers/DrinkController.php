<?php

namespace App\Http\Controllers;

use App\Models\drink;
use App\Http\Requests\StoredrinkRequest;
use App\Http\Requests\UpdatedrinkRequest;

class DrinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoredrinkRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(drink $drink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(drink $drink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedrinkRequest $request, drink $drink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(drink $drink)
    {
        //
    }
}
