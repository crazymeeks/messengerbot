<?php

namespace App\Models\Data;

use MongoDB\BSON\ObjectId;
use App\Models\Data\BaseData;
use App\Models\Catalog as CatalogModel;

class Catalog extends BaseData
{
    

    /**
     * @inheritDoc
     */
    public function save()
    {
        $catalog = new CatalogModel();
        
        $fields = [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'sku' => $this->getSku(),
            'price' => $this->getPrice(),
            'image_urls' => $this->getImageUrls(),
            'discount_price' => $this->getDiscountPrice(),
            'status' => $this->getStatus(),
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ];
        
        $fields = $this->removeGuadedFields($fields);
        
        if ($id = $this->getId()) {
            $result = $catalog->updateOne(['_id' => new ObjectId($id)], [
                '$set' => $fields
            ]);
        } else {
            $result = $catalog->insertOne($fields);
            $id = $result->getInsertedId()->__toString();
        }

        $this->setId($id);


        return $this;
        
    }
}