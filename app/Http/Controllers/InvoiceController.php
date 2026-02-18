<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Table;
use App\Models\Order;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvoiceController extends Controller
{

    public function index(): View
    {
        $query = Invoice::with('table');

        if (request()->filled('price_range')) {
            $range = request('price_range');

            if (str_contains($range, '-')) {
                [$min, $max] = explode('-', $range);
                $query->whereBetween('total', [(float)$min, (float)$max]);
            }

            if ($range === '500+') {
                $query->where('total', '>=', 500);
            }
        }

        $invoices = $query->paginate(10)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $tables = Table::all();
        $orders = Order::all();
        return view('invoices.create', compact('tables', 'orders'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $invoice = Invoice::create($request->validated());
        return redirect()->route('invoices.index')
            ->with('success', 'Factura creada correctamente.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load('table', 'order');

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $tables = Table::all();
        $orders = Order::all();
        return view('invoices.edit', compact('invoice', 'tables', 'orders'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $invoice->update($request->validated());
        return redirect()->route('invoices.index')
            ->with('success', 'Factura actualizada correctamente.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Factura eliminada correctamente.');
    }
}
