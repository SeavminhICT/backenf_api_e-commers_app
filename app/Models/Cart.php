<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_price',
        'status'
    ];

    public function items(){
        return $this->hasMany(CartItem::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
