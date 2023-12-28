<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'created_at',
        'updated_at',
    ];
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
