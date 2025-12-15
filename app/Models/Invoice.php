<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Invoice extends Model
{
    use HasFactory;
    protected $fillable = ['table_id', 'total', 'date'];

    protected $casts = [
        'total' => 'decimal:2',
        'date' => 'date',
    ];

    public function order()
    {
        return $this->hasOne(Order::class, 'invoice_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
}
