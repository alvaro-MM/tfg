<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Allergen;

class Dish extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'ingredients', 'image', 'price', 'available', 'special', 'category_id'];

    protected $casts = [
        'available' => 'boolean',
        'special' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function allergens()
    {
        return $this->belongsToMany(Allergen::class, 'dish_allergen', 'dish_id', 'allergen_id');
    }

    // single allergen FK removed in favor of pivot table `dish_allergen`

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
