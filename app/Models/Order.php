<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'subtotal',
        'tax',
        'shipping_fee',
        'total',
        'status',
        'payment_method',  // added
    ];


    public function items(){
        return $this->hasMany(OrderItem::class);
    }


    public function user(){
        return $this->belongsTo(User::class);
    }
}
