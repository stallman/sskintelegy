<?php

namespace App\Console;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Http;

class  ScinsbackMarket
{

    public static function buildSignature($params, $secret_key)
    {
        ksort($params);

        $paramsString = '';
        foreach($params AS $key => $value)
        {
            if($key == 'sign') continue;
            if(is_array($value)) { continue; }
            $paramsString .= $key .':'. $value .';';
        }
        $sign = hash_hmac('sha1', $paramsString, $secret_key);

        return $sign;
    }


    public function __invoke() {
        Log::debug('Shedule: Skinsback ...');

        $apiURL = 'https://skinsback.com/api.php';

        $params = [
            'method' => 'market_pricelist',
            'shopid' => env('SKINSBACK_ID'),
            'game' => 'csgo',
            'extended' => '1'
        ];

        $secret_key = env('SKINSBACK_SECRET');

        $params['sign'] = $this->buildSignature($params, $secret_key);

        $headers = [
            'X-header' => 'value'
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $params);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
        Log::debug($responseBody);
        if ($responseBody['status'] == 'success') {
            DB::beginTransaction();
            Product::query()->update(['amount' => 0]);
            foreach ($responseBody['items'] as $ob) {
                $product = Product::where('name', $ob['name'])->first();
                if ($product) {
                    $product->price = floatval($ob['price']);
                    $product->amount = intval($ob['count']);
                    $product->category_id = $product->category_id;
                    $product->update();
                    Log::debug('Shedule: Skinsback product '.$product->name." updated ");
                }
            }
            DB::commit();
        } else {
            Log::debug('Shedule: Skinsback error update market: '.$responseBody['error_message']);
        }
    }
}
