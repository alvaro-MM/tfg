<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'discount', 'menu_id'];

    public function menu()
    {
        return $this->hasOne(Menu::class, 'offer_id');
    }
}
