<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'price', 'offer_id'];

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_menu', 'menu_id', 'dish_id');
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }

    public function tables()
    {
        return $this->hasMany(Table::class, 'menu_id');
    }
}
