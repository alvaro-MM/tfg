<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    protected $fillable = ['name', 'price', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function allergens()
    {
        return $this->belongsToMany(Allergen::class, 'drink_allergen', 'drink_id', 'allergen_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'drink_order', 'drink_id', 'order_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'drink_id');
    }
}
