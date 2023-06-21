<?php

use App\Http\Controllers\Api\TradeBotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth part
Route::group(
    ['middleware' => 'auth'],
    function () {
        Route::post('/innerpayment', [MyController::class, 'pay'])->name('innerpayment');
        Route::get('/inventory', [MyController::class, 'inventory'])->name('inventory');
        Route::get('/log', [MyController::class, 'log'])->name('log');
        Route::post('/profile', [MyController::class, 'profile'])->name('profile');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/inventory', [MyController::class, 'inventory'])->name('withdraw');
    }
);

Route::name('payment.')->prefix('payment')->group(function () {
        Route::get('/success', [PaymentController::class, 'success'])->name('success');
        Route::get('/fail', [PaymentController::class, 'fail'])->name('fail');
        Route::post('/{type}/callback', [PaymentController::class, 'callback'])->name('callback');
        Route::post('/{type}/create/', [PaymentController::class, 'create'])->middleware('auth')->name('create');
    }
);

Route::name('api.')->prefix('api')->group(function () {
        Route::name("trade-bot.")->prefix('trade-bot')->group(function () {
            Route::put('/status', [TradeBotController::class, 'updateStatus']);
            Route::get('/trades', [TradeBotController::class, 'indexTrades']);
        });
});



// Static pages
Route::get('/terms', function () { return view(app()->getLocale().'.terms');});
Route::get('/about', function () { return view(app()->getLocale().'.about');});
Route::get('/faq', function () {return view(app()->getLocale().'.faq');});
Route::get('/contacts', function () {return view(app()->getLocale().'.contacts');});

// Open part
Route::get('/', [MainController::class, 'index'])->name('main');
Route::get('/catalog/{id}', [ProductController::class, 'show'])->name('card');
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');
Route::any('/my/register', [AuthController::class, 'register'])->name('my.register');
Route::any('/my/login', [AuthController::class, 'login'])->name('my.login');
Route::any('/locale/set/{lang}', [MainController::class, 'switchLocale']);

