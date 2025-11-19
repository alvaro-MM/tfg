<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnedDishDetail extends Model
{
    use HasFactory;
    protected $fillable = ['dish_id', 'returned_dish_id'];

    public function returnedDish()
    {
        return $this->belongsTo(ReturnedDish::class, 'returned_dish_id');
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class, 'dish_id');
    }
}
