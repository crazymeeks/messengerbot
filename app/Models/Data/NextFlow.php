<?php

namespace App\Models\Data;

use App\Models\NextFlow as NextFlowModel;
use MongoDB\BSON\ObjectId;
use App\Models\Data\BaseData;

class NextFlow extends BaseData
{

    /**
     * @inheritDoc
     */
    public function save()
    {
        $nextflow = new NextFlowModel();

        $fields = [
            'bot' => $this->getBotType(),
            'bot_user_id' => $this->getBotUserId(),
            'next' => $this->getNext(),
            'custom_data' => $this->getCustomData(),
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ];

        $fields = $this->removeGuadedFields($fields);
        
        $existingFlow = $nextflow->findOne(['bot_user_id' => $this->getBotUserId()]);
    
        if ($existingFlow) {    
            $this->setId($existingFlow->_id->__toString());
        }
        if ($id = $this->getId()) {
            $result = $nextflow->updateOne(['_id' => new ObjectId($id)], [
                '$set' => $fields
            ]);
        } else {
            $result = $nextflow->insertOne($fields);
            $id = $result->getInsertedId()->__toString();
        }

        $this->setId($id);

        return $this;
        
    }
}