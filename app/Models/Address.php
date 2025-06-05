<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;
    protected $fillable = [
        'line1',
        'line2',
        'city',
        'phone',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
    ];
}
