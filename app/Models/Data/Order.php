<?php

namespace App\Models\Data;

use App\Models\Cart;
use App\Models\Catalog;
use App\Models\OrderCatalog;
use App\Models\Data\BaseData;
use App\Models\Order as OrderModel;

class Order extends BaseData
{
    
    public function save()
    {
        
    
        $order = new OrderModel();

        $fields = [
            'user_id' => $this->getUserId(), // could be user's facebook
            'reference_number' => $this->getReferenceNumber(),
            'firstname' => $this->getFirstName(),
            'lastname' => $this->getLastName(),
            'email' => $this->getEmail(),
            'mobile_number' => $this->getMobileNumber(),
            'shipping_address' => $this->getShippingAddress(),
            'status' => $this->getStatus(),
            'payment_method' => $this->getPaymentMethod(),
            'payment_status' => $this->getPaymentStatus(),
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ];

        $fields = $this->removeGuadedFields($fields);
        $result = $order->insertOne($fields);
        $id = $result->getInsertedId()->__toString();
        $this->saveOrderItems($result);
        $this->setId($id);

        return $this;
    }

    private function saveOrderItems($order)
    {

        $shoppingCart = new Cart();
        $cartItems = $shoppingCart->find(['user_id' => $this->getUserId()])->toArray();
        $orderItems = [];
        $grandTotal = 0;
        foreach($cartItems as $item){
            $catalog = new Catalog();
            $cat = $catalog->findOne(['_id' => $item->catalog_id]);
            $orderItems[] = [
                'order_id' => $order->getInsertedId(),
                'catalog_name' => $cat->name,
                'sku' => $cat->sku,
                'price' => $cat->price,
                'quantity' => $item->quantity,
                'created_at' => now()->__toString(),
                'updated_at' => now()->__toString(),
            ];
            $grandTotal += $cat->price * $item->quantity;
        }
        $this->setGrandTotal($grandTotal);
        $orderCatalog = new OrderCatalog();
        $orderCatalog->insertMany($orderItems);
        
    }

    public static function generateReferenceNumber()
    {
        return date('Y') . '-' . strtoupper(uniqid());
    }
}