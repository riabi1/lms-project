<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    // Relation avec les commandes (orders)
    public function orders()
    {
        return $this->hasMany(Order::class, 'payment_id', 'id');
    }
}