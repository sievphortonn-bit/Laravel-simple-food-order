<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchantbakongkhqr extends Model
{
    use HasFactory;
    protected $fillable = [
        'merchantType',
        'bakongAccountID',
        'transactionCurrency',
        'countryCode',
        'merchantName',
        'merchantCity',
        'crc',
    ];


    public static function createNonNull($data)
    {
        // Filter out null values
        $filteredData = array_filter($data, function ($value) {
            return !is_null($value);
        });

        // Create and save the merchant record
        return self::create($filteredData);
    }
}
