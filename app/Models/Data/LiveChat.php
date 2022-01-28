<?php

namespace App\Models\Data;

use App\Models\Chatter;
use MongoDB\BSON\ObjectId;
use App\Models\Data\BaseData;
use App\Models\ConversationReply;

class LiveChat extends BaseData
{
    const ADMIN_REPLY = '1';

    /**
     * @inheritDoc
     */
    public function save()
    {
        $pageId = $this->getPageId();
        $recipient_id = $this->getRecipientId();
        $message = $this->getMessage();
        $request = $this->getRequest();

        $chatter = new Chatter();

        $chatter = $chatter->findOne(['fb_id' => $recipient_id]);
        
        if ($chatter) {
            
            $reply = new ConversationReply();
            $reply->updateOne(['chatter_id' => $chatter->_id], ['$set' => ['answered_by_admin' => self::ADMIN_REPLY]]);
            
            $result = $reply->insertOne([
                'chatter_id' => $chatter->_id,
                'admin_user_id' => admin_user()->_id,
                'reply' => $message,
                'answered_by_admin' => self::ADMIN_REPLY,
                'stime' => time(),
            ]);
            
            $this->setId($result->getInsertedId()->__toString());
        }

        return $this;
        
    }
}