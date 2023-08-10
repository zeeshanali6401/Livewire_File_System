<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class file extends Model
{
    protected $fillable = [
        'name', 
        'file',
    ];
    
    use HasFactory;
}
