<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Table;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $invoices = Invoice::with('table')->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tables = Table::all();

        return view('invoices.create', compact('tables'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $invoice = Invoice::create($request->validated());

        return redirect()->route('invoices.index')
            ->with('success', 'Factura creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load('table', 'order');

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice): View
    {
        $tables = Table::all();

        return view('invoices.edit', compact('invoice', 'tables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $invoice->update($request->validated());

        return redirect()->route('invoices.index')
            ->with('success', 'Factura actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Factura eliminada correctamente.');
    }
}
