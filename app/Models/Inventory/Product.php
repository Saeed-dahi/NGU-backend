<?php

namespace App\Models\Inventory;

use App\Http\Traits\SharedFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, SharedFunctions;

    protected $fillable = [
        'ar_name',
        'en_name',
        'code',
        'description',
        'barcode',
        'file',
        'type',
        'category_id',
    ];

    protected $casts = [
        'file' => 'array'
    ];


    public function setFileAttribute($value)
    {
        $attribute_name = "file";
        $disk = "public";
        $destination_path = "uploads/product-files/" . str_replace(' ', '_', $this->en_name);

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
