<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'messenger', 'namespace' => 'Messenger'], function($router){
    $router->group(['prefix' => 'webview'], function($router){
        $router->post('/add-to-cart', 'WebViewController@addToCart')->name('messenger.webview.add.to.cart');
        $router->get('/{senderid}/{brand}/{pageid}/order-now', 'WebViewController@getOrderViewForm')->name('messenger.webview.order-now');

        $router->post('/checkout', 'WebViewController@postCheckout')->name('messenger.webview.order.checkout');
    });

    /**
     * Route where response from messenger will be posted
     */
    // $router->post('/reply', 'MessengerController@parseMessengerReply')->name('web.messenger.parse.reply');
});