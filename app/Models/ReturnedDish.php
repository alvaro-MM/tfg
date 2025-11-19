<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ReturnedDish extends Model
{
    use HasFactory;
    protected $fillable = ['table_id', 'invoice_id', 'reason'];

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function details()
    {
        return $this->hasMany(ReturnedDishDetail::class, 'returned_dish_id');
    }
}
