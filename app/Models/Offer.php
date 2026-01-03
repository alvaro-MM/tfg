<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'discount', 'menu_id'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
