<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;
<<<<<<< HEAD

    protected $fillable = ['order_id', 'table_id', 'total', 'date'];
=======
    protected $fillable = [
        'order_id', 
        'table_id', 
        'total', 
        'date',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_notes',
        'payment_method',
        'payment_status',
        'payment_reference',
        'payment_amount',
        'payment_currency',
    ];
    
    protected $casts = [
        'date' => 'datetime',
        'total' => 'decimal:2',
    ];
>>>>>>> qr-menu-orders

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
