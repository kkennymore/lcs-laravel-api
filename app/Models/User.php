<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'account_type',
        'remember_token'
    ];

    /*jion the location table */
    public function getLocation()
    {
        return $this->hasOne(Locations::class,"user_id");
    }

    /*jion the orders table */
    public function getOrders()
    {
        return $this->hasOne(Orders::class,"user_id");
    }
    /*jion the product table */
    public function getProducts()
    {
        return $this->hasOne(Products::class,"user_id");
    }
    /*jion the cart table */
    public function getCart()
    {
        return $this->hasOne(Carts::class,"user_id");
    }
}
