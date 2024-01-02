<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'company_name',
        'jpy',
        'vnd',
        'usd',
        'exchange_rate_id',
        'payment_date',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function exchange_rates(){
        return $this->belongsTo(ExchangeRate::class);
    }
}
