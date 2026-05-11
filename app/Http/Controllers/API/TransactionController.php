<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
   public function all (Request $request){
       $id = $request->input('id');
       $status = $request->input('status');
       $limit = $request->input('limit');
    
        if($id){
           $transaction = Transactions::with('transactionDetails.product')->find($id);

           if($transaction){
                return ResponseFormatter::success(
                    $transaction,
                    'Data transaksi berhasil diambil'
                );
              }else{
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ditemukan',
                    404
                );
              }
           }
        
        $transaction = Transactions::with('transactionDetails.product')->where('users_id', Auth::user()->id);
        
        if($status){
            $transaction->where('status', $status);
        }

        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'Data list transaksi berhasil diambil'
        );
    }
}


