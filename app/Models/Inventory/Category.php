<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['ar_name', 'en_name', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
