<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Table extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'capacity',
        'status',
        'notes',
        'user_id',
        'menu_id',
        'qr_token'
    ];

    /**
     * Booted model hook to ensure each table has a QR token
     */
    protected static function booted(): void
    {
        static::created(function (Table $table) {
            if (!$table->qr_token) {
                $table->generateQrToken();
            }
        });
    }

    /**
     * Generate a unique QR token for the table
     */
    public function generateQrToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('qr_token', $token)->exists());

        $this->qr_token = $token;
        $this->save();

        return $token;
    }

    /**
     * Scope to find table by QR token
     */
    public function scopeByQrToken($query, string $token)
    {
        return $query->where('qr_token', $token);
    }

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
