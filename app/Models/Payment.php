<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'name',
        'cost',
        'currency_type',
        'note',
        'invoice',
        'pay',
        'category_id',
        'exchange_rate_id',
        'payment_date',
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function exchange_rates(){
        return $this->belongsTo(ExchangeRate::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
