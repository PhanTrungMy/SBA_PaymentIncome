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
<<<<<<< HEAD
        "vnd",
=======
        'vnd',
>>>>>>> b2546537a43569cb6861c02ac42097528d349cc1
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
