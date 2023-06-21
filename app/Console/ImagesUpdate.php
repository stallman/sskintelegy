<?php

namespace App\Console;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Sunra\PhpSimple\HtmlDomParser;

class ImagesUpdate
{

    public function __invoke() {

        Log::debug('Shedule: ImagesUpdate ...');

            $doc = new \DOMDocument();
            $i=0;
            foreach (Product::whereNull('imageurl')->where('amount','>',0)->get() as $product) {
                $i++;
                $response = Http::get("https://steamcommunity.com/market/listings/730/".$product->name);

                try {
                    $doc->loadHTML($response);
                    $finder = new \DomXPath($doc);
                    $node = $finder->query("//*[contains(@class, 'market_listing_largeimage')]/img/@src[1]");
                    if ($node->item(0)) {

                        Log::debug("Shedule: ImagesUpdate set imageurl: ");
                        Log::debug($node->item(0)->nodeValue);
                        $src = NULL;
                        $product->imageurl = $node->item(0)->nodeValue;
                        $product->update();
                    }
                } catch(Exception $e) {
                     Log::debug($e->getMessage());
                }
                sleep(2);
                if ($i>2000) break;

            }

    }
}