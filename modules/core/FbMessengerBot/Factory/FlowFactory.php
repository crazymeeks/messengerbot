<?php

/**
 * This class is reponsible for creating instance of the
 * next reply to messenger depending on the action
 * taken by the user
 */

namespace FbMessengerBot\Factory;

use App\Models\FacebookFlow;
use App\Models\MessengerFlowNextStep;
use FbMessengerBot\HttpClient\Server;

class FlowFactory
{

    private $flows = [];

    public function __construct(Server $server, \stdClass $payload)
    {
        $fb_flow = new FacebookFlow();
        $fb_flow = $fb_flow->findOne();

        

    }


    

    public function get()
    {
        return $this->flows;
    }
}