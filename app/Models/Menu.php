<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'price', 'offer_id'];

    protected $casts = [
        'price' => 'decimal:2',
        'type' => 'string',
    ];

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_menu', 'menu_id', 'dish_id')
            ->withPivot('is_special', 'custom_price');
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }

    public function tables()
    {
        return $this->hasMany(Table::class, 'menu_id');
    }

    /**
     * Get the total price including offer discount
     */
    public function getTotalPriceAttribute(): float
    {
        $basePrice = $this->price;
        if ($this->offer) {
            $discount = $this->offer->discount / 100;
            $basePrice = $basePrice * (1 - $discount);
        }
        return $basePrice;
    }

    /**
     * Check if menu has active offer
     */
    public function hasActiveOffer(): bool
    {
        return $this->offer !== null;
    }

    /**
     * Get menu type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'daily' => 'Diario',
            'special' => 'Especial',
            'seasonal' => 'Estacional',
            'themed' => 'Temático',
            default => ucfirst($this->type)
        };
    }

    /**
     * Get the price of a dish for this menu
     * If the dish is special, use custom_price, otherwise use menu price
     * Returns null if dish is not found in this menu (for fallback handling)
     */
    public function getDishPrice($dishId): ?float
    {
        $dishMenu = $this->dishes()
            ->where('dish_id', $dishId)
            ->first();
        
        if (!$dishMenu) {
            // Dish not found in this menu
            return null;
        }

        // If it's a special dish with custom price, use that
        if ($dishMenu->pivot->is_special && $dishMenu->pivot->custom_price !== null) {
            return (float) $dishMenu->pivot->custom_price;
        }

        // Otherwise, use the menu's price
        return (float) $this->price;
    }

    /**
     * Get the effective price of a dish (from dish or menu)
     * Returns the dish's own price if not associated with menu, otherwise uses getDishPrice
     */
    public static function getDishPriceInMenu($dishId, $menuId): float
    {
        $menu = self::find($menuId);
        if (!$menu) {
            return 0.00;
        }
        return $menu->getDishPrice($dishId);
    }

}
