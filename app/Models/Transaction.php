<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'users_id', 'name', 'email', 'address', 'phone', 'courier','total_berat', ' payment', 'payment_url', 'total_price', 'status', 'service_courier', 'cost', 'city_origin', 'city_destination'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
