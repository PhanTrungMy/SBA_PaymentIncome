<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceSheet extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'bs_month_year',
        'category_id',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
