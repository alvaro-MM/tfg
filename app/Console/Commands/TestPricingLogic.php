<?php

namespace App\Console\Commands;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Console\Command;

class TestPricingLogic extends Command
{
    protected $signature = 'test:pricing {--table-id=1}';
    protected $description = 'Test pricing logic with real data from database';

    public function handle()
    {
        $tableId = $this->option('table-id');
        
        if ($tableId === '1') {
            // Find a table with pending orders
            $table = Table::query()
                ->join('orders', 'tables.id', '=', 'orders.table_id')
                ->where('orders.invoice_id', null)
                ->with(['menu', 'orders'])
                ->select('tables.*')
                ->distinct()
                ->first();
            
            if (!$table) {
                $this->error("No tables with pending orders found");
                return;
            }
        } else {
            $table = Table::with(['menu', 'orders'])->find($tableId);

            if (!$table) {
                $this->error("Table {$tableId} not found");
                return;
            }
        }

        $this->info("Testing pricing logic for Table: {$table->name}");
        $this->info("Menu: " . ($table->menu?->name ?? 'None'));
        $this->info("Menu Price: €" . number_format($table->menu?->price ?? 0, 2));
        $this->newLine();

        // Get pending orders
        $orders = Order::with(['dishes', 'drinks'])
            ->where('table_id', $table->id)
            ->whereNull('invoice_id')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No pending orders found');
            return;
        }

        $this->info("Found {$orders->count()} pending orders:");
        $this->newLine();

        $totalAll = 0;

        foreach ($orders as $order) {
            $this->info("Order #{$order->id}:");
            $orderTotal = 0;

            foreach ($order->dishes as $dish) {
                $price = $dish->price;
                $source = 'Dish Price';

                if ($table->menu) {
                    $menuPrice = $table->menu->getDishPrice($dish->id);
                    if ($menuPrice !== null) {
                        $price = $menuPrice;
                        $source = 'Menu Price';
                    }
                }

                $itemTotal = $price * $dish->pivot->quantity;
                $orderTotal += $itemTotal;

                $this->line("  - {$dish->name} x{$dish->pivot->quantity} @ €" . number_format($price, 2) . " ({$source}) = €" . number_format($itemTotal, 2));
            }

            foreach ($order->drinks as $drink) {
                $price = $drink->price;
                $itemTotal = $price * $drink->pivot->quantity;
                $orderTotal += $itemTotal;

                $this->line("  - {$drink->name} x{$drink->pivot->quantity} @ €" . number_format($price, 2) . " = €" . number_format($itemTotal, 2));
            }

            $this->info("Order Total: €" . number_format($orderTotal, 2));
            $totalAll += $orderTotal;
            $this->newLine();
        }

        $this->info("Total for all orders: €" . number_format($totalAll, 2));
        $this->info("Using calculateTotal() method: €" . number_format(
            $orders->sum(fn($o) => $o->calculateTotal($table->menu)),
            2
        ));
    }
}
