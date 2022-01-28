<?php

namespace Reports\Sales\Tests;

use App\Models\Order;
use App\Models\OrderCatalog;
use Reports\Sales\SalesReport;
use Reports\Factory\ReportFactory;

class SalesReportTest extends \Tests\TestCase
{
    public function testGetLifeTimeSales()
    {
        $this->createOrder();

        $report = ReportFactory::make(SalesReport::class);

        $data = $report->getData();

        $this->assertArrayHasKey('lifetime_sales', $data);
    }

    public function testGetMonthlySales()
    {
        $this->createOrder();

        $report = ReportFactory::make(SalesReport::class);
        $data = $report->getMonthly();

        $this->assertArrayHasKey('labels', $data);
        $this->assertArrayHasKey('series', $data);
    }

    public function testGetSalesCurrentMonth()
    {
        $this->createOrder();

        $report = ReportFactory::make(SalesReport::class);
        $sales = $report->getCurrentMonth();
        $this->assertEquals(75, $sales);
    }

    public function testGetMostSalesCatalog()
    {
        $this->createOrder();
        $order = new Order();
        $insertResult = $order->insertOne([
            'user_id' => '12345',
            'reference_number' => '34343',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'mobile_number' => '098989098987',
            'shipping_address' => 'shipping address',
            'status' => Order::CO,
            'payment_method' => 'Dragonpay',
            'payment_status' => Order::CO,
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ]);

        $orderItem = new OrderCatalog();
        $orderItem->insertMany([
            [
                'order_id' => $insertResult->getInsertedId(),
                'catalog_name' => 'Catalog 2',
                'sku' => 'Catalog 2',
                'price' => 25,
                'quantity' => 3,
                'created_at' => now()->__toString(),
                'updated_at' => now()->__toString(),
            ],
            [
                'order_id' => $insertResult->getInsertedId(),
                'catalog_name' => 'Catalog 2',
                'sku' => 'Catalog 2',
                'price' => 25,
                'quantity' => 3,
                'created_at' => now()->__toString(),
                'updated_at' => now()->__toString(),
            ]
        ]);

        $insertResult = $order->insertOne([
            'user_id' => '12345',
            'reference_number' => '67788',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'mobile_number' => '098989098987',
            'shipping_address' => 'shipping address',
            'status' => Order::CO,
            'payment_method' => 'Dragonpay',
            'payment_status' => Order::CO,
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ]);

        $orderItem = new OrderCatalog();
        $orderItem->insertOne([
            'order_id' => $insertResult->getInsertedId(),
            'catalog_name' => 'Catalog 4',
            'sku' => 'Catalog 4',
            'price' => 25,
            'quantity' => 3,
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ]);
        $report = ReportFactory::make(SalesReport::class);
        $catalogs = $report->getMostSaleableCatalog();
        $this->assertEquals('Catalog 1', $catalogs[0]['sku']);
        $this->assertEquals('Catalog 1', $catalogs[0]['catalog_name']);
        $this->assertEquals(1, $catalogs[0]['count']);
    }

    /**
     * Get total of orders who actually paid(complete)
     * and those incomplete
     */
    public function testGetConvertedAndUnpaid()
    {
        $this->createOrder();
        $order = new Order();

        $insertResult = $order->insertOne([
            'user_id' => '12345',
            'reference_number' => '67788',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'mobile_number' => '098989098987',
            'shipping_address' => 'shipping address',
            'status' => Order::PE,
            'payment_method' => 'Dragonpay',
            'payment_status' => Order::PE,
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ]);

        $orderItem = new OrderCatalog();
        $orderItem->insertOne([
            'order_id' => $insertResult->getInsertedId(),
            'catalog_name' => 'Catalog 4',
            'sku' => 'Catalog 4',
            'price' => 25,
            'quantity' => 3,
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ]);
        $report = ReportFactory::make(SalesReport::class);
        $catalogs = $report->getConvertedAndUnpaid();
        $this->assertArrayHasKey('complete', $catalogs['series']);
        $this->assertArrayHasKey('pending', $catalogs['series']);
        $this->assertArrayHasKey('cancelled', $catalogs['series']);
        $this->assertArrayHasKey('complete', $catalogs['series']);
        $this->assertArrayHasKey('processing', $catalogs['series']);
    }

    private function createOrder()
    {
        $order = new Order();
        $insertResult = $order->insertOne([
            'user_id' => '12345',
            'reference_number' => '123445',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'mobile_number' => '098989098987',
            'shipping_address' => 'shipping address',
            'status' => Order::CO,
            'payment_method' => 'Dragonpay',
            'payment_status' => Order::CO,
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ]);

        $orderItem = new OrderCatalog();
        $orderItem->insertOne([
            'order_id' => $insertResult->getInsertedId(),
            'catalog_name' => 'Catalog 1',
            'sku' => 'Catalog 1',
            'price' => 25,
            'quantity' => 3,
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ]);
    }
}