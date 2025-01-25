<?php

namespace App\Models;

use App\Services\AccountService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Journal extends Model
{
    use HasFactory;

    protected $fillable = ['voucher_number', 'description', 'document', 'status', 'date'];

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }


    protected static function boot()
    {
        parent::boot();

        static::updating(function ($journal) {});
    }
}
