<?php

namespace App\Models;

use App\Http\Traits\SharedFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountInformation extends Model
{
    use HasFactory, SharedFunctions;

    protected $fillable = [
        'account_id',
        'phone',
        'mobile',
        'fax',
        'email',
        'contact_person_name',
        'address',
        'barcode',
        'description',
        'info_in_invoice',
        'file',
    ];

    protected $casts = [
        'file' => 'array'
    ];

    public function setFileAttribute($value)
    {
        $attribute_name = "file";
        $disk = "public";
        $destination_path = "uploads/account-files/" . str_replace(' ', '_', $this->account->en_name);

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    function account()
    {
        return $this->belongsTo(Account::class);
    }
}
