<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;

    /**this will return a location that belongs to a specific user */
    public function getLocationsOwner(){
        return $this->belongsTo(User::class,  'user_id','id');
     }

}
