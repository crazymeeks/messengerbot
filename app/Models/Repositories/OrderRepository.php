<?php

namespace App\Models\Repositories;

use App\Models\Repositories\BaseRepository;
use App\Models\Order;
use App\Models\OrderCatalog;

class OrderRepository extends BaseRepository
{
    public function getDataTableData()
    {
        $limit = (int) $this->getDataTableLimit();
        $offset = $this->getDataTableOffset();
        $search = $this->getDataTableSearch();
        list($column, $direction) = $this->getDataTableOrder();
        
        $order = new Order();
        
        $total = $order->find()->toArray();
        
        $recordsFiltered = $total;
        $request = $this->getRequest();
        if ($search) {

            $recordsFiltered = $order->find([
                '$or' => [
                    [
                        'firstname' => new \MongoDB\BSON\Regex($search),
                    ],
                    [
                        'lastname' => new \MongoDB\BSON\Regex($search),
                    ],
                    [
                        'mobile_number' => new \MongoDB\BSON\Regex($search),
                    ]
                ]
            ], [
                'limit' => $limit
            ])->toArray();
            
        }

        $filter = $request->has('filter') && $request->filter['state'] ? $request->filter['state'] : null;

        if ($filter) {
            $recordsFiltered = $order->find([
                '$or' => [
                    [
                        'status' => new \MongoDB\BSON\Regex($filter),
                    ]
                ]
            ], [
                'limit' => $limit
            ])->toArray();
        }

        $totalRecords = count($total);
        $data = [
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => count($recordsFiltered),
            'data' => []
        ];

        

        $orders = [];

        foreach($recordsFiltered as $result){
            
            $orders[] = [
                'reference_number' => $result->reference_number,
                'firstname' => $result->firstname,
                'lastname' => $result->lastname,
                'email' => $result->email,
                'mobile_number' => $result->mobile_number,
                'payment_method' => $result->payment_method,
                'status' => $result->status,
                'payment_status' => $result->payment_status,
                'created_at' => $result->created_at,
                '_id' => $result->_id->__toString(),
            ];
            
        }

        $data['data'] = $orders;

        return $data;

    }
}