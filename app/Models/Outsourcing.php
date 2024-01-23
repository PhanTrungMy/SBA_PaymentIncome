<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Outsourcing extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'company_name',
        'jpy',
        'usd',
        'vnd',
        'exchange_rate_id',
        'outsourced_project',
        'outsourced_date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function exchange_rates()
    {
        return $this->belongsTo(ExchangeRate::class);
    }
    public function setOutsourcedDateAttribute($value)
    {
        if ($value) {
            try {
                $this->attributes['outsourced_date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
            } catch (\Exception $e) {
                $this->attributes['outsourced_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
            }
        }
    }
}
