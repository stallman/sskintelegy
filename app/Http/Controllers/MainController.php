<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class MainController extends Controller
{
    public function index() {

        $arProductsNew = Product::where('amount','>',0)->orderBy("created_at", "desc")->limit(4)->get();
        $arProductsTop = Product::where('amount','>',0)->orderBy("buys", "desc")->limit(4)->get();
        
        return view('main',  compact('arProductsNew', 'arProductsTop'));
    }

    public function switchLocale($lang)
    {
        if (!in_array($lang, config('app.available_locales'))) {
            abort(400);
        }

        App::setLocale($lang);

        session()->put('locale', $lang);
        Cookie::queue(Cookie::forever('lang', $lang));

        return response()->json(['message' => 'success']);
    }
}