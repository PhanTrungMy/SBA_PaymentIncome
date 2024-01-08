<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'company_name',
        'jpy',
        'usd',
        "vnd",
        'exchange_rate_id',
        'order_date',
    ];
    public function exchange_rate(){
        return $this->belongsTo(ExchangeRate::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
