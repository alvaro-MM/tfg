<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergen extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'image'];

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_allergen', 'allergen_id', 'dish_id');
    }

    public function drinks()
    {
        return $this->belongsToMany(Drink::class, 'drink_allergen', 'allergen_id', 'drink_id');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = \Str::slug($value);
    }
    
}