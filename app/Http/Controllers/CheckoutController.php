<?php

namespace App\Http\Controllers;

use App\Providers\Cart;
use Exception;
use Midtrans\Snap;
use App\Providers\Transaction;
use Midtrans\Config;
use App\Providers\TransactionDetail;
use Midtrans\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        // TODO: Save users data
        $user = Auth::user();
        $user->update($request->except('total_price'));

        // Proses checkout
        $code = 'STORE-' . mt_rand(0000,9999);
        $carts = Cart::with(['product','user'])
                    ->where('users_id', Auth::user()->id)
                    ->get();

        $transaction = Transaction::create([
            'users_id' => Auth::user()->id,
            'inscurance_price' => 0,
            'shipping_price' => 0,
            'total_price' => $request->total_price,
            'transaction_status' => 'PENDING',
            'code' => $code
        ]);

        foreach ($carts as $cart) {
            $trx = 'TRX-' . mt_rand(0000,9999);

            TransactionDetail::create([
                'transactions_id' => $transaction->id,
                'products_id' => $cart->product->id,
                'price' => $cart->product->price,
                'shipping_status' => 'PENDING',
                'resi' => '',
                'code' => $trx
            ]);
        }

        // Delete cart data
        Cart::with(['product','user'])
                ->where('users_id', Auth::user()->id)
                ->delete();

        return redirect()->route('home');


        
    }

    public function callback(Request $request)
    {
       
        // Cari transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($order_id);

     
        
    }
}
