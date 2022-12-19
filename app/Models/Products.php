<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    /**this is get all the images associated to a product */
    public function getProductImages(){
        return $this->hasMany(ProductsImages::class,'product_id');
    }
    /*this will fetch the owner of the product */
    public function getProductsOwner(){
        return $this->belongsTo(User::class,  'user_id','id');
     }

     /**this is get all the orders associated to a product */
    public function getProductsOrders(){
        return $this->hasMany(Orders::class,'product_id');
    }
}
