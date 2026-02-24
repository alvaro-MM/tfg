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
     * Calcula el total de un pedido aplicando la misma lógica que el comando
     * de consola TestPricingLogic (menú buffet + extras + bebidas).
     */
    public function calculateTotal(?\App\Models\Menu $menu = null): float
    {
        $total = 0.00;

        /** @var \App\Models\Table|null $table */
        $table = $this->getTableInstance();

        // Obtener menú desde la mesa si no se pasa explícito
        if (!$menu && $table) {
            $menu = $table->menu;
        }

        // 1) Precio base del menú (una vez por pedido/cliente)
        if ($menu) {
            $total += (float) $menu->price;
        }

        // 2) Platos:
        //    - Normales del menú: incluidos (0€)
        //    - Especiales del menú: extra (custom o precio plato)
        //    - Fuera de menú: extra a precio normal
        foreach ($this->dishes as $dish) {
            $quantity = $dish->pivot->quantity ?? 1;

            if ($menu) {
                $menuDish = $menu->dishes()->where('dish_id', $dish->id)->first();

                if ($menuDish) {
                    if ($menuDish->pivot->is_special) {
                        $extra = $menuDish->pivot->custom_price ?? $dish->price;
                        $total += $extra * $quantity;
                    }
                    // Platos normales del menú no suman nada extra
                } else {
                    // Plato fuera de menú
                    $total += $dish->price * $quantity;
                }
            } else {
                // Sin menú: siempre se cobra el plato
                $total += $dish->price * $quantity;
            }
        }

        // 3) Bebidas: primera gratis, resto de pago (por pedido)
        $drinkCount = 0;
        foreach ($this->drinks as $drink) {
            $prev = $drinkCount;
            $qty = $drink->pivot->quantity ?? 0;
            $drinkCount += $qty;

            // Cuántas de este drink son de pago (después de la primera global)
            $chargeable = max(0, min($qty, $drinkCount - 1 - $prev));
            $total += $chargeable * $drink->price;
        }

        return round($total, 2);
    }

    /**
     * Get the table associated with the order.
     */
    public function getTableInstance(): ?Table
    {
        // Use the relationship explicitly; do not access $this->table directly
        return $this->table()->first();
    }
}
