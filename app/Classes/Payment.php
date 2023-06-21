<?php

namespace App\Classes;

use App\Models\Order;
use Illuminate\Http\Request;

abstract class Payment
{
    protected $apiKey;
    protected $apiEndpoint;

    abstract public function processPayment(Order $order, $data);
    abstract public function verify(Request $request);
}
