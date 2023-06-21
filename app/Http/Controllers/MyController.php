<?php

namespace App\Http\Controllers;

use App\Console\ScinsbackMarket;
use App\Models\Bot;
use App\Models\Order;
use App\Models\Product;
use App\Models\Trade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MyController extends Controller
{

    private function addMargin($price) {
        if ($price<=10) return $price*1.5;
        if ($price<=100) return $price*1.3;
        if ($price<=500) return $price*1.2;
        if ($price<=1000) return $price*1.1;
        if ($price<=5000) return $price*1.05;
        if ($price>5000) return $price*1.02;
    }

    public function pay(Request $request)
    {
        if ($request->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'id' => [
                    'required',
                    'integer',
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->route('card', $request['id'])->withErrors($validator);
            }

            $user = Auth::user();

            $product = Product::findOrFail($request['id']);
            if ($user->balance >= $product->price) {

                //DB::beginTransaction();
                $bot = Bot::first();
                parse_str($bot->link, $arr);

                $apiURL = 'https://skinsback.com/api.php';

                $params = [
                    'method' => 'market_buy',
                    'shopid' => env('SKINSBACK_ID'),
                    'game' => 'csgo',
                    'partner' => $arr['?partner'],
                    'token' => $arr['token'],
                    'max_price' => $this->addMargin($product->price),
                    'name' => ''.$product->name,
                ];

                $secret_key = env('SKINSBACK_SECRET');
                $params['sign'] = ScinsbackMarket::buildSignature($params, $secret_key);
                $headers = ['X-header' => 'value'];

                $response = Http::withHeaders($headers)->post($apiURL, $params);
                Log::debug('Trade skinsback response: '.$response);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);
                if ($responseBody['status'] == 'success') {

                    $ob = $responseBody['item'];

                    $product->amount = $product->amount - 1;
                    $product->update();
                    Log::debug('Shedule: Skinsback product buy success '.$product->name." ");

                    $user->balance -= $product->price;
                    $user->update();
                    $product->users()->attach(
                        $user->id,
                        [
                            'status' => Trade::STATUS_PURCHASED,
                            'trade_id' => $responseBody['buy_id'],
                            'asset_id' => $ob['instanceid'],
                            'buycost' => $product->price,
                            'bot_id' => $bot->id,
                        ]
                    );

                } else {
                    Log::debug('Shedule: Skinsback error update market: '.$responseBody['error_message']);

                    return redirect()->route('card', $request['id'])->withErrors('Partner error');
                }

            } else {
                return redirect()->route('card', $request['id'])->withErrors(__('menu.buy_error_balance'));
            }

            return redirect()->route('inventory',)->withSuccess(__('menu.buy_success'));


        }
    }

    public function profile(Request $request)
    {
        if ($request->isMethod('POST')) {

            $validator = Validator::make($request->all(), [
                'password' => [
                    'max:255',
                ],
                'name' => [
                    'required',
                    'string',
                    'max:250',
                ],
                'steamapi' => [
                    'max:250',
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['valid_error' => $validator->errors()]);
            }


            $user = Auth::user();
            $user->name = $request->name;
            $user->steamapi = $request->steamapi;

            if ($request['password']) {
                $request['password'] = Hash::make($request['password']);
                $user->password = $request->password;
            }

            $user->update();

            //(new \App\Services\MailService())->send($request->email, "Regisration on ", $message, $from);

            return response()->json(['location' => route('main')]);

        }
    }


    public function inventory(Request $request)
    {
        $user = Auth::user();
        $products = $user->products()->get();
        $errors = [];

        if (is_array($request->ids) && $request->isMethod('POST')) {
            $tradesId = array_filter(
                array_keys($request->ids),
                static fn($id) => is_numeric($id) && !empty($id)
            );

            $query = Trade::query()
                ->whereIn('id', $tradesId)
                ->whereIn('status', [Trade::STATUS_STORAGE, Trade::STATUS_ERROR])->where('possible_widthdraw_at',
                    '<=',
                    Carbon::now()
                )
            ;


            $isValidTrades = $query->count() === count($tradesId);
            if (!$isValidTrades) {
                $errors[] = 'Not possible widthdraw it items now';
            }

            if ($isValidTrades) {
                $query->update(['status' => Trade::STATUS_PENDING]);
            }
        }

        return view('inventory', compact('products'))->withErrors($errors);
    }

    public function log(Request $request)
    {

        $user = Auth::user();
        $logs = Order::where('user_id', $user->id)->where('status', 1)->orderBy('id', 'desc')->get();

        return view('log', compact('logs'));
    }


}
