<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'backend/auth', 'namespace' => 'Backend'], function($router){
    $router->group(['namespace' => 'Authentication'], function ($router) {
        $router->get('/login', 'LoginController')->name('admin.login');
        $router->post('/post-login', 'LoginController@postLogin')->name('admin.post.login');
        $router->post('/post-logout', 'LoginController@postLogout')->name('admin.post.logout');
    });
});
Route::group(['prefix' => 'backend', 'namespace' => 'Backend', 'middleware' => ['backend.auth']], function ($router) {
    $router->get('/', 'HomeController@index')->name('admin.home');

    # Live chat(admin reply to user's messenger straight from backend)
    $router->group(['prefix' => 'conversation', 'namespace' => 'Conversation'], function($router){
        $router->get('/messages', 'LiveChatController@messages')->name('admin.messenger.messages');
        $router->group(['prefix' => 'livechat'], function($router){
            $router->get('/{id}/conversation', 'LiveChatController@viewConversation')->name('admin.config.messenger.message.view');
            $router->post('/', 'LiveChatController@postReply')->name('admin.messenger.chat.reply');
            $router->get('/{id}/feed', 'LiveChatController@getLiveFeed')->name('admin.messenger.chat.get.livefeed');
            $router->post('/end-live-chat', 'LiveChatController@endLiveChat')->name('admin.messenger.end.live.chat');
        });
        $router->get('/datatable', 'LiveChatController@getConversationDataTable')->name('admin.messenger.get.conversation');

        $router->group(['prefix' => 'user'], function($router){
            $router->post('/', 'LiveChatController@checkIfLiveChatMode')->name('admin.messenger.customer.need.live.chat');
        });
    });


    # Facebook flow
    $router->group(['prefix' => 'facebook', 'namespace' => 'Facebook'], function($router){
        $router->get('/', 'FlowController@index')->name('admin.facebook.flow.index');
        $router->post('/', 'FlowController@postCreateFlow')->name('admin.facebook.flow.post.create');
    });

    # Catalog
    $router->group(['prefix' => 'catalog', 'namespace' => 'Catalog'], function($router){
        $router->get('/create', 'CatalogController@create')->name('admin.catalog.get.create');
        $router->post('/', 'CatalogController@postCreate')->name('admin.catalog.post.create');
        $router->get('/edit/{id}', 'CatalogController@edit')->name('admin.catalog.get.edit');
        $router->post('/delete', 'CatalogController@postDelete')->name('admin.catalog.post.delete');
        $router->post('/toggle-activate', 'CatalogController@toggleActivate')->name('admin.catalog.post.toggle.activate');
        $router->get('/list', 'CatalogController@list')->name('admin.catalog.listing');
        $router->get('/datatable', 'CatalogController@dataTable')->name('admin.catalog.datatable');

        # Image upload
        $router->post('/upload-image', 'CatalogController@uploadImage')->name('admin.catalog.image.upload');
        # Delete image
        $router->post('/delete-image', 'CatalogController@ajaxDeleteImage')->name('admin.catalog.image.delete');
    });

    # Order
    $router->group(['prefix' => 'order', 'namespace' => 'Order'], function($router){
        $router->get('/', 'OrderController@listing')->name('admin.order.listing');
        $router->get('/datatable', 'OrderController@dataTable')->name('admin.order.datatable');
        $router->get('/edit/{id}', 'OrderController@edit')->name('admin.order.edit');
        
        $router->post('/update-status', 'OrderController@updateStatus')->name('admin.order.update.status');
        $router->post('/update-payment-status', 'OrderController@updatePaymentStatus')->name('admin.order.update.payment.status');
    });
});
