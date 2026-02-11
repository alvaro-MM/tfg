<?php

namespace App\Http\Traits;

use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ManagesBuffetLimit
{
    /**
     * Validate buffet limit: 5 items per person in a sliding 10-minute window.
     * This version is optimized to reduce query complexity.
     */
    public function validateBuffetLimit(Table $table, int $newItemsCount): array
    {
        $limit = 5 * $table->capacity;
        $tenMinutesAgo = Carbon::now()->subMinutes(10);

        // Step 1: Get IDs of all orders placed for this table in the last 10 minutes.
        $recentOrderIds = DB::table('orders')
            ->where('table_id', $table->id)
            ->where(function ($query) use ($tenMinutesAgo) {
                // Use orders.date if available, otherwise fallback to created_at
                $query->where('date', '>=', $tenMinutesAgo)
                      ->orWhere(function ($q) use ($tenMinutesAgo) {
                          $q->whereNull('date')
                            ->where('created_at', '>=', $tenMinutesAgo);
                      });
            })
            ->pluck('id');

        $totalRecentItems = 0;
        if ($recentOrderIds->isNotEmpty()) {
            // Step 2: Sum quantities from dishes and drinks for those recent orders.
            $recentDishes = DB::table('dish_order')
                ->whereIn('order_id', $recentOrderIds)
                ->sum('quantity');

            $recentDrinks = DB::table('drink_order')
                ->whereIn('order_id', $recentOrderIds)
                ->sum('quantity');

            $totalRecentItems = $recentDishes + $recentDrinks;
        }

        $availableSlots = max(0, $limit - $totalRecentItems);

        return [
            'valid' => $newItemsCount <= $availableSlots,
            'total_recent' => $totalRecentItems,
            'limit' => $limit,
            'available' => $availableSlots,
            'requested' => $newItemsCount,
        ];
    }
}