<?php

namespace FbMessengerBot\Exceptions;


use Log;

class BotResponseException extends \Exception
{

    public function __construct($message, $code = 500)
    {
        Log::error($message);

    }
}