<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentOrder extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'company_name',
        'jpy',
        'vnd',
        'usd',
        'exchange_rate_id',
        'payment_date',
        'deleted_at'

    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function exchange_rates(){
        return $this->belongsTo(ExchangeRate::class);
    }
}
