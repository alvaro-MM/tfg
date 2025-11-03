<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    public function dishes()
    {
        return $this->hasMany(Dish::class, 'category_id');
    }

    public function drinks()
    {
        return $this->hasMany(Drink::class, 'category_id');
    }
}
