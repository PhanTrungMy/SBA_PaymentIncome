<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [ 
        'name',
        'description',
        'group_id'
    ];
    public function group(){
        return $this->belongsTo(Group::class);
    }
    public function payments(){
        return $this->hasMany(Payment::class);
    }

}
