<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TradeBotController extends Controller
{
    public function updateStatus(Request $request): Response
    {
        Log::debug('Trade API '.$request->getContent());

        $data = $request->json()->all();

        $botToken = $request->get('botToken');

        $fromPendingStatuses = [
            Trade::STATUS_PROCESSING,
            Trade::STATUS_WITHDRAWN,
            Trade::STATUS_ERROR,
        ];

        $validStatuses = array_merge([
            Trade::STATUS_STORAGE,
        ], $fromPendingStatuses);

        assert(in_array($data['status'], $validStatuses, true));

        if ($data['status'] === Trade::STATUS_STORAGE) {
            return response()->json(['status' => 'success', 'data' => null]);
        }

        $updateData = [
            'status' => $data['status'],
        ];

        $assetsId = array_map(static fn($item) => (int)$item['asset_id'], $data['items']);
        Log::debug('Trade API ', $assetsId);

        Log::debug('Trade API ', $assetsId);

        Trade::query()
//                ->where('context_id', '=', $item['context_id'])
            ->whereIn('asset_id', $assetsId)
            ->where('bots.token', '=', $botToken)
            ->join('bots', 'bot_id', '=', 'bots.id')
            ->update($updateData);

        return response()->json(['status' => 'success', 'data' => null]);
    }

    public function indexTrades(Request $request): Response
    {
        $botToken = $request->get('botToken');

        $trades = Trade::query()
            ->where('status', '=', Trade::STATUS_PENDING)
            ->where('bots.token', '=', $botToken)
            ->join('bots', 'bot_id', '=', 'bots.id')
            ->join('users', 'user_id', '=', 'users.id')
            ->get();

        $result = [];
        foreach ($trades as $trade) {
            $fields = [
                $trade->user->steamapi,
                $trade->context_id,
                $trade->asset_id,
            ];

            if (in_array('', $fields, true)) {
                continue;
            }

            $result[$trade->user->steamapi] [] = [
                'context_id' => $trade->context_id,
                'asset_id' => $trade->asset_id,
            ];
        }

        return response()->json(
            ['status' => 'success', 'data' => $result]
        );
    }
}
