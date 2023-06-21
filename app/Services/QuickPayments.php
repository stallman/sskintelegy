<?php

namespace App\Services;

use App\Classes\Payment;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class QuickPayments extends Payment
{

    function __construct()
    {
        $this->apiKey = config('quickpayments.token');
        $this->apiEndpoint = config('quickpayments.endpoint');
    }

    public function processPayment(Order $order, $cardDetails)
    {
        $body = [
            "order_id" => $order->id,
            "email" => $order->user->email,
            "amount" => $order->amount,
            "currency" => $order->currency,
            "return_url" => route('payment.success', ['type' => 'quickpayments']),
            "callback_url" => route('payment.callback', ['type' => 'quickpayments']),
        ];

        $response = $this->request()->post($this->apiEndpoint . "/payment_sessions",  $body);
        $arrayResponse = json_decode($response->body(), true);

        return $arrayResponse;
    }


    public function verify(Request $request)
    {
        $sign = $request->header('x-signature');
        $hashedData = hash_hmac('sha256', $request->getContent(), $this->apiKey);
        // \Log::info($request->getContent());
        // \Log::info($sign);
        // \Log::info($hashedData);
        // \Log::info($this->apiKey);
        return $sign == $hashedData;
    }

    protected function request()
    {
        return Http::withHeaders([
            'Authorization' => 'Token token=' . $this->apiKey,
        ]);
    }
}
