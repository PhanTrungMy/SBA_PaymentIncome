<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeRate extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'jpy',
        'usd',
        'exchange_rate_month',
    ];
    public function payments(){
        return $this->belongsTo(Payment::class);
    }
    public function orders(){
        return $this->belongsTo(Order::class);
    }
    public function payment_orders(){
        return $this->belongsTo(PaymentOrder::class);
    }
    public function outsourcing(){
        return $this->belongsTo(Outsourcing::class);
    }

}
