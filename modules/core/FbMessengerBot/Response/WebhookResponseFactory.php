<?php

namespace FbMessengerBot\Response;

class WebhookResponseFactory
{

    const RESPONSES = [
        'standby' => \FbMessengerBot\Response\StandbyMode::class,
        'messaging' => \FbMessengerBot\Response\MessagingMode::class,
    ];

    public static function make(array $messenger_response_data)
    {
        $class = self::RESPONSES['messaging'];
        
        if (isset($messenger_response_data['standby'])) {
            $class = self::RESPONSES['standby'];
        }

        return new $class($messenger_response_data);

    }
}