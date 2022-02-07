<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id', 'type', 'first_name', 'last_name', 'phone_number',
        'email', 'street', 'city', 'state', 'postal_code', 'country_code',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
