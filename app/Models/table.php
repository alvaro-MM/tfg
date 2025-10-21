<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['number', 'user_id', 'menu_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'table_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    public function returnedDishes()
    {
        return $this->hasMany(ReturnedDish::class, 'table_id');
    }
}
