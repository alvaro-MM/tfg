<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
        'type' => 'string',
    ];

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_menu', 'menu_id', 'dish_id');
    }

    public function offer()
    {
        return $this->hasOne(Offer::class);
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
}
