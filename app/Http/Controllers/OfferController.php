<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Menu;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $offers = Offer::with('menu')->paginate(10);

        return view('offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $menus = Menu::all();

        return view('offers.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferRequest $request): RedirectResponse
    {
        $offer = Offer::create($request->validated());

        return redirect()->route('offers.index')
            ->with('success', 'Oferta creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $offer): View
    {
        $offer->load('menu');

        return view('offers.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer): View
    {
        $menus = Menu::all();

        return view('offers.edit', compact('offer', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferRequest $request, Offer $offer): RedirectResponse
    {
        $offer->update($request->validated());

        return redirect()->route('offers.index')
            ->with('success', 'Oferta actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer): RedirectResponse
    {
        $offer->delete();

        return redirect()->route('offers.index')
            ->with('success', 'Oferta eliminada correctamente.');
    }
}
