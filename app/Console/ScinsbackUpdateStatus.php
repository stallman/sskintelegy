<?php

namespace App\Console;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Http;
#use App\Services\Interfaces\MailServiceInterface;
use App\Models\Trade;
use App\Console\ScinsbackMarket;

class  ScinsbackUpdateStatus
{

    public function __invoke() {
        Log::debug('Shedule: Skinsback ...');

        $apiURL = 'https://skinsback.com/api.php';
        $secret_key = env('SKINSBACK_SECRET');

        $trades = DB::table('product_user')->where('status', Trade::STATUS_PURCHASED)->whereNotNull('trade_id')->get();

        foreach ($trades as $trade) {

            $params = [
                'method' => 'market_getinfo',
                'shopid' => env('SKINSBACK_ID'),
                'buy_id' => $trade->trade_id
            ];

            $params['sign'] = ScinsbackMarket::buildSignature($params, $secret_key);
            $headers = ['X-header' => 'value'];
            $response = Http::withHeaders($headers)->post($apiURL, $params);

            $statusCode = $response->status();
            $responseBody = json_decode($response->getBody(), true);
            Log::debug($responseBody);

            if ($responseBody['status'] == 'success') {
                if ($responseBody['offer_status'] == 'accepted') {
                    DB::table('product_user')->where(['id' => $trade->id])
                            ->update(['status' => Trade::STATUS_STORAGE, 'possible_widthdraw_at' => Carbon::now()->addMinutes('+7 days')]);
                }
                if ($responseBody['offer_status'] == 'canceled' || 
                    $responseBody['offer_status'] == 'invalid_trade_token' || 
//                    $responseBody['offer_status'] == 'timeout' || 
                    $responseBody['offer_status'] == 'user_not_tradable' || 
                    $responseBody['offer_status'] == 'trade_create_error') {
                    
                    DB::table('product_user')->where(['id' => $trade->id])
                            ->update(['status' => Trade::STATUS_CANCELLED]);
                    $user = User::findOrFail($trade->user_id);
                    $user->balance += $trade->buycost;
                    $user->update();
                }
            } else {
                Log::debug('Shedule: Skinsback error update status : '.$responseBody['error_message']);
            }
        }
    }
}
