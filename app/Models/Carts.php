<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    use HasFactory;

    /**this will return the owner of an item in the cart */
    public function getCartsOwner(){
        return $this->belongsTo(User::class,  'user_id','id');
     }
}
