<?php

namespace App\Models\Repositories;

use App\Models\Catalog;
use App\Models\Repositories\BaseRepository;

class CatalogRepository extends BaseRepository
{
    

    public function getDataTableData()
    {
        $limit = (int) $this->getDataTableLimit();
        $offset = $this->getDataTableOffset();
        $search = $this->getDataTableSearch();
        list($column, $direction) = $this->getDataTableOrder();
        
        $catalog = new Catalog();
        
        $total = $catalog->find()->toArray();
        
        $recordsFiltered = $total;

        if ($search) {
            $recordsFiltered = $catalog->find(['name' => ['$regex' => $search]], [
                'limit' => $limit
            ])->toArray();
        }

        $request = $this->getRequest();
        $totalRecords = count($total);
        $data = [
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => count($recordsFiltered),
            'data' => []
        ];

        $catalogs = [];

        foreach($recordsFiltered as $result){
            
            $catalogs[] = [
                'name' => $result->name,
                'sku' => $result->sku,
                'price' => "&#8369;" . number_format($result->price, 2),
                'discount_price' => $result->discount_price ? "&#8369;" . number_format($result->discount_price, 2) : 'Discount not applicable',
                'status' => $result->status == Catalog::ACTIVE ? 'Active' : 'Inactive',
                '_id' => $result->_id->__toString(),
            ];
            
        }

        $data['data'] = $catalogs;

        return $data;

    }
    
}