<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailResets extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'otp_code',
        'type',
        'updated_at',
    ];
}
