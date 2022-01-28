<?php

namespace FbMessengerBot\Response;

use Illuminate\Http\Request;
use FbMessengerBot\HttpClient\Server;

abstract class AbstractMode
{
    protected $page_id;

    protected $parameters = [];

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;

        $this->page_id = $this->parameters['id'];
    }

    protected function getRealSenderId($message)
    {
        if ($this->page_id == $message['sender']['id']) {
            $id = $message['recipient']['id'];
        } else {
            $id = $message['sender']['id'];
        }

        return $id;
    }

    /**
     * Send response to facebook
     *
     * @param \FbMessengerBot\HttpClient\Server $server
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Server $server, Request $request)
    {
        throw new \Exception(get_class($this) . ' does not implement send() method.');
    }
}