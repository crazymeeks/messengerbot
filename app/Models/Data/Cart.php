<?php

namespace App\Models\Data;

use App\Models\Cart as CartModel;
use MongoDB\BSON\ObjectId;
use App\Models\Data\BaseData;
use App\Models\ConversationReply;

class Cart extends BaseData
{
    
    /**
     * @inheritDoc
     */
    public function save()
    {
        
        
        $cart = new CartModel();

        $fields = [
            'catalog_id' => new ObjectId($this->getCatalogId()),
            'user_id' => $this->getUserId(),
            'quantity' => $this->getQuantity(),
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ];

        $fields = $this->removeGuadedFields($fields);
        
        $existingCartItem = $cart->findOne(['user_id' => $this->getUserId(), 'catalog_id' => $fields['catalog_id']]);
        
        if ($existingCartItem) {
            $existingQty = (int) $existingCartItem->quantity + (int) $this->getQuantity();
            $fields = [
                'quantity' => $existingQty
            ];
            $this->setId($existingCartItem->_id->__toString());
        }

        if ($id = $this->getId()) {
            $result = $cart->updateOne(['_id' => new ObjectId($id)], [
                '$set' => $fields
            ]);
        } else {
            $result = $cart->insertOne($fields);
            $id = $result->getInsertedId()->__toString();
        }

        $this->setId($id);

        return $this;
        
    }
}