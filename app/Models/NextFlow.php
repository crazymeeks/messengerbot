<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class NextFlow extends Model
{
    
    const BOT_MESSENGER = 'messenger';

    const BOT_VIBER = 'viber';

    protected $collection = 'next_flow';

    protected $fillable = [
        'bot',
        'bot_user_id',
        'next',
        'custom_data', // custom data for the next steps
    ];

    /**
     * This flow may be a validation of user's required next flow
     *
     * @param string $flow The next flow action the user should take
     * @param string $bot_user_id
     * @param string $bot Bot type(e.g viber|messenger)
     * 
     * @return mixed
     */
    public function saveNextFlow(string $flow, string $bot_user_id, $custom_data = null, string $bot = self::BOT_MESSENGER)
    {
        $result = $this->findOne(['bot_user_id' => $bot_user_id]);

        $data = [
            'bot' => $bot,
            'bot_user_id' => $bot_user_id,
            'next' => $flow,
            'custom_data' => $custom_data,
        ];

        if ($result) {
            $data = [
                '$set' => [
                    'next' => $flow
                ]
            ];
            $cond = [
                'bot_user_id' => $bot_user_id
            ];

            return $this->updateOne($cond, $data);
        }
        
        return $this->insertOne($data);
    }

    public function removeNextFlow(string $bot_user_id)
    {
        return $this->deleteOne([
            'bot_user_id' => $bot_user_id
        ]);
    }
}
