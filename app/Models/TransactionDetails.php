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

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transactions_id', 'id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
