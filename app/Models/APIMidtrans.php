<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class APIMidtrans extends Model
{
    use HasFactory;
    protected $fillable = [
        'merchant_id',
        'client_key',
        'server_key',
    ];
}
