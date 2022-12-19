<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;


    /**return the user associated to an order */
    public function getOrdersOwner(){
       return $this->belongsTo(User::class, 'user_id','id');
    }
}
