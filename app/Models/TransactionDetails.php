<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetails extends Model
{
    protected $table = 'transaction_details';

    protected $fillable = [
        'transactions_id',
        'products_id',
        'user_id',
        'quantity',
    ];
}
