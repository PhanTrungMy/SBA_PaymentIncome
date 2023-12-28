<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'reportType',
      
    ];
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
