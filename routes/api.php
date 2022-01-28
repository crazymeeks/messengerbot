<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api\v1'], function($router){
    $router->group(['prefix' => 'generate'], function($router){
        
        $router->group(['prefix' => 'payment'], function($router){

            $router->group(['prefix' => 'dragonpay'], function($router){
                $router->post('/link', 'DragonpayPaymentLinkGeneratorController@generateLinkAndCreateOrder')->name('dragonpay.generate.paylink');

            });
        });
    });

    $router->group(['prefix' => 'catalog'], function($router){
        $router->get('/list', 'CatalogController@list')->name('api.v1.catalog.list');
    });

    $router->group(['namespace' => 'Messenger'], function($router){
        // Messenger Web Hook
        $router->group(['prefix' => 'messenger'], function($router){
            $router->get('/webhook', 'WebhookController@getWebHook')->name('api.v1.messenger.get.webhook');
            $router->post('/webhook', 'WebhookController@postWebHook')->name('api.v1.messenger.post.webhook');
        });
    
        # Shopping cart
        $router->group(['prefix' => 'cart'], function($router){
            $router->post('/', 'CartController@postAdd')->name('api.post.add.to.cart');
        });

    });

});