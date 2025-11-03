<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['order_id', 'table_id', 'total', 'date'];

    public function order()
    {
        return $this->hasOne(Order::class, 'invoice_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
}
