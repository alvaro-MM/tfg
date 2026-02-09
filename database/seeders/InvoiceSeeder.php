<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create invoices for only 50% of orders, leaving others pending for payment testing
        $allOrders = Order::all();
        $count = (int)($allOrders->count() * 0.5);

        $ordersToInvoice = Order::query()
            ->inRandomOrder()
            ->limit($count)
            ->get();

        foreach ($ordersToInvoice as $order) {
            // Load relationships
            $order->load(['table.menu', 'dishes', 'drinks']);

            // Calculate total from order using the menu
            $menu = $order->table?->menu;
            $total = 0;

            foreach ($order->dishes as $dish) {
                $price = $dish->price;
                if ($menu) {
                    $menuPrice = $menu->getDishPrice($dish->id);
                    if ($menuPrice !== null) {
                        $price = $menuPrice;
                    }
                }
                $total += $price * $dish->pivot->quantity;
            }

            foreach ($order->drinks as $drink) {
                $total += $drink->price * $drink->pivot->quantity;
            }

            $invoice = Invoice::create([
                'order_id' => $order->id,
                'table_id' => $order->table_id,
                'total' => round($total, 2),
                'date' => $order->date ?? now(),
                'customer_name' => fake()->name(),
                'customer_email' => fake()->email(),
                'customer_phone' => fake()->phoneNumber(),
                'payment_method' => fake()->randomElement(['cash', 'card', 'mobile']),
                'payment_status' => 'completed',
            ]);

            // Update order with invoice_id
            $order->update(['invoice_id' => $invoice->id]);
        }

        $year = now()->year;

        DB::table('invoices')->insert([
            [
                'order_id' => 1,
                'table_id' => 1,
                'total' => 4200,
                'date' => Carbon::create($year, 1, 15),
                'payment_status' => 'completed',
            ],
            [
                'order_id' => 1,
                'table_id' => 1,
                'total' => 1800,
                'date' => Carbon::create($year, 2, 15),
                'payment_status' => 'completed',
            ],
            [
                'order_id' => 1,
                'table_id' => 1,
                'total' => 1500,
                'date' => Carbon::create($year, 3, 15),
                'payment_status' => 'completed',
            ],
        ]);
    }
}
