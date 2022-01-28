<?php

namespace App\Models\Repositories;

use App\Models\NextFlow;
use App\Models\Repositories\BaseRepository;


class NextFlowRepository extends BaseRepository
{

    private $nextFlow;

    public function __construct(NextFlow $nextFlow)
    {
        $this->nextFlow = $nextFlow;
    }

    /**
     * Delete flow of a user
     * 
     * @param string $user_id This is facebook id of the user
     * 
     * @return void
     */
    public function deleteUserFlow(string $user_id)
    {
        $this->nextFlow->deleteOne(['bot_user_id' => $user_id]);
    }

    /**
     * Check if user initiated live chat
     *
     * @param string $user_id
     * 
     * @return bool
     */
    public function checkIfLiveChatMode(string $user_id)
    {
        $result = $this->nextFlow->findOne(['bot_user_id' => $user_id]);

        if ($result) {
            return $result->next == \FbMessengerBot\HttpClient\Server::ON_LIVE_SUPPORT_MODE;
        }

        return false;
    }
}