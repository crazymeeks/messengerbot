<?php

namespace Chatbot\Api;


use FbMessengerBot\HttpClient\Server;

interface FlowInterface
{

    /**
     * Get response that will be send back to messenger
     *
     * @param \FbMessengerBot\HttpClient\Server $server
     * 
     * @return array
     */
    public function getResponse(Server $server): array;
}