<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('users_id', 'status', 'payment', 'address', 'total_price', 'total_shipping' )]
class Transactions extends Model
{
   protected $table = 'transactions';
}
