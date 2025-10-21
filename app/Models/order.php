<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'table_id', 'invoice_id', 'type', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_order', 'order_id', 'dish_id');
    }

    public function drinks()
    {
        return $this->belongsToMany(Drink::class, 'drink_order', 'order_id', 'drink_id');
    }
}
