<?php

namespace App\Models;

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
}
