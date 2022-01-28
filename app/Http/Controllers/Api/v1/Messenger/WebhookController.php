<?php

namespace App\Http\Controllers\Api\v1\Messenger;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use FbMessengerBot\HttpClient\Server;
use FbMessengerBot\Response\WebhookResponseFactory;

class WebhookController extends Controller
{
    
    protected $curl;
    private $server;
    private $page_id;

    public function __construct(
        Server $server
    )
    {
        $this->server = $server;
    }

    /**
     * Facebook messenger post webhook
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postWebHook(Request $request)
    {   
        if ($request->has('object')) {
            $entry = $request->entry[0];
            $response = WebhookResponseFactory::make($entry);
            
            return $response->send($this->server, $request);
        }

        return response($request->hub_challenge);
    }

    /**
     * Facebook messenger get webhook
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWebHook(Request $request)
    {

        \Log::info($request->all());
        return response($request->hub_challenge);
    }


    
}
