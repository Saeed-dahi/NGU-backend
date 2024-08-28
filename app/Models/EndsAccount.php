<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndsAccount extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'ar_name', 'en_name'];
}
