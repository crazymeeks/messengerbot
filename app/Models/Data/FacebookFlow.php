<?php

namespace App\Models\Data;

use MongoDB\BSON\ObjectId;
use App\Models\Data\BaseData;
use App\Models\FacebookFlow as FlowModel;
use App\Http\Controllers\Backend\Authentication\LoginController;

class FacebookFlow extends BaseData
{

    /**
     * @inheritDoc
     */
    public function save()
    {
        $flow = $this->getFlow();
        $model = new FlowModel();
        if ($id = $this->getId()) {
            $model->updateOne(['_id' => new ObjectId($id)], ['$set' => ['flow' => $flow]]);
        } else {
            $result = $model->insertOne([
                'flow' => $flow
            ]);

            $id = $result->getInsertedId()->__toString();
        }

        $this->setId($id);

        return $this;
        
    }
}