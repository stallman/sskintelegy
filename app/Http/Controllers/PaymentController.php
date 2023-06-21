<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function create(Request $request, $type)
    {
        $payments = config('payments');
        $data = $request->all();
        $request->validate([
            'amount' =>  'required|numeric|min:0|not_in:0',
        ]);

        $order = new Order();
        $order->amount = $request->amount;
        // $order->currency = $request->currency;
        $order->user_id  = Auth::user()->id;
        $order->type  = $type;
        $order->save();

        $order = Order::findOrFail($order->id);

        $payment = new $payments[$type];

        $response = $payment->processPayment($order, $data);

        if (isset($response['url'])) {
            Session::flash('redirectUrl', $response['url']);
        } else {
            Session::flash('paymentFail', 1);
        }

        return redirect()->back();
    }

    public function success(Request $request)
    {
        return view('payment.success');
    }

    public function fail(Request $request)
    {
        return view('payment.fail');
    }

    public function callback(Request $request, $type)
    {
        $payments = config('payments');
        $payment = new $payments[$type];

        if(!$payment->verify($request)) {
            abort(404);
        }

        $data = $request->all();
        if (isset($data['transaction']['status']) && isset($data['transaction']['order_id'])) {
            if ($data['transaction']['status'] == 'purchase_complete') {
                $order = Order::whereId($data['transaction']['order_id'])->where('status', '!=', 1)->firstOrFail();
                $order->status = 1;
                $order->save();
                if (isset($data['transaction']['amount'])) {
                    $balance = $order->user->balance + $data['transaction']['amount'];
                    $order->user()->update(['balance' => $balance]);
                }
            }
        }
    }
}
