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
           $transaction = Transactions::with('transactionDetails.products')->find($id);

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
        
        $transaction = Transactions::with('transactionDetails.products')->where('users_id', Auth::user()->id);
        
        if($status){
            $transaction->where('status', $status);
        }

        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'Data list transaksi berhasil diambil'
        );
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'transactionDetails' => 'required|array',
            'transactionDetails.*.id' => 'exists:products,id',
            'total_price' => 'required',
            'total_shipping' => 'required',
            'address' => 'required|string|max:255',
            'status' => 'required|string|in:PENDING,SUCCESS,FAILED,CANCELED,SHIPPING,SHIPPED',
        ]);


        $transaction = Transactions::create([
            'users_id' => Auth::user()->id,
            'total_price' => $request->total_price,
            'total_shipping' => $request->total_shipping,
            'address' => $request->address,
            'status' => $request->status,
        ]);

       
        foreach($request->transactionDetails as $detail) {
                $transaction->transactionDetails()->create([
                    'users_id' => Auth::user()->id,
                    'products_id' => $detail['id'],
                    'transactions_id' => $transaction->id,
                    'quantity' => $detail['quantity'],
                ]);
            }

            return ResponseFormatter::success(
                $transaction->load('transactionDetails.products'),
                'Transaksi berhasil'
            );

    }
}


