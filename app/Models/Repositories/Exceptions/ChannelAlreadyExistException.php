<?php

namespace App\Models\Repositories\Exceptions;

class ChannelAlreadyExistException extends \Exception
{

    public function __construct($message = 'Oops. This channel already exist', $code = 500)
    {
        parent::__construct($message, $code);
    }
}