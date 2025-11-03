<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $fillable = ['name', 'ingredients', 'available', 'special', 'price', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function allergens()
    {
        return $this->belongsToMany(Allergen::class, 'dish_allergen', 'dish_id', 'allergen_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'dish_menu', 'dish_id', 'menu_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'dish_order', 'dish_id', 'order_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'dish_id');
    }
}
