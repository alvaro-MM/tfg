<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'table_id', 'invoice_id', 'type', 'date'];
    
    protected $casts = [
        'date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_order', 'order_id', 'dish_id')
            ->withPivot('quantity', 'created_at', 'updated_at')
            ->withTimestamps();
    }

    public function drinks()
    {
        return $this->belongsToMany(Drink::class, 'drink_order', 'order_id', 'drink_id')
            ->withPivot('quantity', 'created_at', 'updated_at')
            ->withTimestamps();
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }


    /**
     * Calculate the total price of a single order
     * Adjusted to account for the number of people at the table
     */
    public function calculateTotal(?\App\Models\Menu $menu = null): float
    {
        $total = 0.00;

        // Get the menu if not provided
        if (!$menu) {
            $menu = $this->table?->menu;
        }

        // Ensure the table relationship is loaded
        $table = $this->table;

        // Use the table's getPeopleCount method to determine the number of people
        $peopleCount = $table->getPeopleCount();
        \Log::debug('People count: ' . $peopleCount);

        // Ensure menu price is multiplied by the number of people
        if ($menu) {
            $menuPrice = $menu->price;
            $total += $menuPrice * $peopleCount;
            \Log::debug('Total after menu price calculation: ' . $total);
        }

        // Add dish extras: only special dishes attached to the menu add extra price
        foreach ($this->dishes as $dish) {
            $quantity = $dish->pivot->quantity ?? 1;

            if ($menu) {
                $menuDish = $menu->dishes()->where('dish_id', $dish->id)->first();
                if ($menuDish && $menuDish->pivot->is_special) {
                    $extraPrice = $menuDish->pivot->custom_price ?? $dish->price;
                    $total += $extraPrice * $quantity;
                }
            } else {
                $total += $dish->price * $quantity;
            }
        }

        // Refine drink calculation logic
        $totalDrinks = $this->drinks->sum(fn($drink) => $drink->pivot->quantity ?? 0);
        $chargeableDrinks = max(0, $totalDrinks - $peopleCount);
        $total += $chargeableDrinks * ($this->drinks->first()->price ?? 0);

        \Log::debug('Final total: ' . $total);
        return round($total, 2);
    }

    /**
     * Get the table associated with the order.
     */
    public function getTableInstance(): ?Table
    {
        return $this->table ?? $this->table()->first();
    }
}
