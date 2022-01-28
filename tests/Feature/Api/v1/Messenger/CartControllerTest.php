<?php

namespace Tests\Feature\Api\v1\Messenger;

use App\Models\Cart;
use App\Models\Catalog;

class CartControllerTest extends \Tests\TestCase
{

    public function testAddItemToCart()
    {
        $catalogId = $this->createCatalog();
        $data = [
            'catalog_id' => $catalogId,
            'user_id' => '1001',
            'quantity' => 2,
        ];

        $this->json('POST', route('api.post.add.to.cart'), $data);

        $cart = new Cart();
        $item = $cart->findOne();
        $this->assertEquals(2, $item->quantity);
        $this->assertEquals('1001', $item->user_id);
        $this->assertInstanceOf(\MongoDB\BSON\ObjectId::class, $item->catalog_id);
    }

    public function testUpdateQuantityOfExistingItemInTheCart()
    {
        $catalogId = $this->createCatalog();

        $this->addToCart($catalogId);

        $data = [
            'catalog_id' => $catalogId,
            'user_id' => '1001',
            'quantity' => 2,
        ];

        $this->json('POST', route('api.post.add.to.cart'), $data);
        $cart = new Cart();

        $item = $cart->findOne();
        
        $this->assertEquals(4, $item->quantity);

    }

    private function addToCart(string $catalogId)
    {
        $cart = new Cart();
        $cart->insertOne([
            'catalog_id' => new \MongoDB\BSON\ObjectId($catalogId),
            'user_id' => '1001',
            'quantity' => 2,
        ]);
    }

    private function createCatalog()
    {
        $catalog = new Catalog();

        $result = $catalog->insertOne([
            'name' => 'Product1',
            'sku' => 'product1',
            'description' => 'Product1 description',
            'price' => 20,
            'discount_price' => 0,
            'status' => Catalog::ACTIVE
        ]);

        return $result->getInsertedId()->__toString();
    }

}