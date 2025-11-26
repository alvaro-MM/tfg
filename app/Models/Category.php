<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'parent_id'];

    public function dishes()
    {
        return $this->hasMany(Dish::class, 'category_id');
    }

    public function drinks()
    {
        return $this->hasMany(Drink::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}