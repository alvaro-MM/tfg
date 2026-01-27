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

    /**
     * Calculate the total price of a single order
     * Each order = 1 client pays the menu price
     * First beverage included, charge from 2nd onwards
     */
    public function calculateTotal(?\App\Models\Menu $menu = null): float
    {
        $total = 0.00;

        // Get the menu if not provided
        if (!$menu) {
            if ($this->relationLoaded('table')) {
                $menu = $this->table?->menu;
            } else {
                $tableMenu = $this->table()->with('menu')->first()?->menu;
                if ($tableMenu) {
                    $menu = $tableMenu;
                }
            }
        }

        // Menu price (fixed per client/order)
        if ($menu) {
            $total += $menu->price;
        }

        // Add drinks (first one free, charge from 2nd onwards)
        $drinkCount = 0;
        foreach ($this->drinks as $drink) {
            $drinkCount += $drink->pivot->quantity;
            // Only charge from 2nd beverage onwards
            $chargeableQuantity = max(0, $drinkCount - 1);
            $total += $drink->price * $chargeableQuantity;
        }

        return round($total, 2);
    }
}
