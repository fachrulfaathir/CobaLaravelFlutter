<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('users_id', 'status', 'payment', 'address', 'total_price', 'total_shipping' )]
class Transactions extends Model
{
   protected $table = 'transactions';

   public function transactionDetails()
   {
       return $this->hasMany(TransactionDetails::class, 'transactions_id', 'id');
   }

   public function user()
   {
       return $this->belongsTo(User::class, 'users_id', 'id');
   }

   
}
