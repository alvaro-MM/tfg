<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'slug' ,'user_id', 'dish_id', 'drink_id', 'comment', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class, 'dish_id');
    }

    public function drink()
    {
        return $this->belongsTo(Drink::class, 'drink_id');
    }
}
