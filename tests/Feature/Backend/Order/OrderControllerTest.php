<?php

namespace Tests\Feature\Backend\Order;

class OrderControllerTest extends \Tests\TestCase
{

    /**
     * @dataProvider order
     */
    public function testUpdateOrderStatus(array $data)
    {
        $order = new \App\Models\Order();
        $result = $order->insertOne($data);
        $data = [
            '_id' => $result->getInsertedId()->__toString(),
            'status' => \App\Models\Order::PR,
        ];

        $response = $this->json('POST', route('admin.order.update.status'), $data);

        $response->assertStatus(200);
    }

    /**
     * @dataProvider order
     */
    public function testCannotUpdateStatusWhenOrderIsComplete(array $data)
    {
        $order = new \App\Models\Order();
        $data['status'] = \App\Models\Order::CO;
        $result = $order->insertOne($data);

        $data = [
            '_id' => $result->getInsertedId()->__toString(),
            'status' => \App\Models\Order::PR,
        ];

        $response = $this->json('POST', route('admin.order.update.status'), $data);

        $response->assertStatus(400);

        $this->assertEquals('Order cannot be updated! Order status may be updated if in Pending or Processing state.', $response->original);

    }

    /**
     * @dataProvider order
     */
    public function testUpdatePaymentStatus(array $data)
    {
        $order = new \App\Models\Order();
        $result = $order->insertOne($data);
        $data = [
            '_id' => $result->getInsertedId()->__toString(),
            'payment_status' => \App\Models\Order::PR,
        ];

        $response = $this->json('POST', route('admin.order.update.payment.status'), $data);

        $response->assertStatus(200);
    }

    public function order()
    {
        $data = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'user_id' => '10001',
            'mobile_number' => '09878987898',
            'payment_method' => 'DragonPay',
            'status' => \App\Models\Order::PE,
            'payment_status' => \App\Models\Order::PE,
            'reference_number' => \App\Models\Data\Order::generateReferenceNumber(),
            'shipping_address' => 'Shipping Address',
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),

        ];

        return [
            [$data]
        ];
    }
}