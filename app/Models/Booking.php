<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'table_id',
        'user_id',
        'offer_id',
        'booking_date',
        'booking_time',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function getBookingTimeFormattedAttribute(): string
    {
        return Carbon::createFromFormat('H:i:s', $this->booking_time)->format('H:i');
    }
}
