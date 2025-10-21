<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnedDishDetail extends Model
{
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
