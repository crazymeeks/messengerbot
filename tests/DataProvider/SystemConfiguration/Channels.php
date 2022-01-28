<?php

namespace Tests\DataProvider\SystemConfiguration;

use Illuminate\Http\Request;

class Channels
{

    public function data()
    {
        
        $request = app(Request::class);

        $request->merge([
            'access_token' => 'the-connected-page-access-token',
            'id' => 'the-connected-page-id',
            'name' => 'the-connected-page-name',
            'type' => \App\Models\Api\ChannelInterface::MESSENGER
        ]);
        
        return [
            array($request)
        ];
    }
}