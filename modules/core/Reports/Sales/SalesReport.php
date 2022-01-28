<?php

namespace Reports\Sales;

use App\Models\Order;
use App\Models\OrderCatalog;


class SalesReport implements \Reports\ReportInterface
{

    private $orderModel;
    private $container = [];


    public function __construct(Order $orderModel)
    {
        $this->orderModel = $orderModel;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        
        $grandTotal = $this->getTotalSales();

        return [
            'lifetime_sales' => $grandTotal
        ];

    }

    /**
     * Get monthly sales. This data will be display in a bar graph
     *
     * @param string|null $year
     * @return array
     */
    public function getMonthly(string $year = null)
    {
        
        $labels = $this->getCalendarLabels();
        
        $year = $year ?? date('Y');
        
        $orders = $this->getSalesByYear($year);

        $__labels = [];
        
        foreach($orders as $order){
            $exploded = explode(' ', $order->created_at);

            $date = array_shift($exploded);
            $date_str = date('M', strtotime($date));
            foreach($order->order_items as $item){
                $total = ($item->price * $item->quantity);
                if (isset($__labels[$date_str])) {
                    $__labels[$date_str] += $total;
                } else {
                    $__labels[$date_str] = $total;
                }
            }
        }
        
        $values = [];

        foreach($labels as $month){
            if (isset($__labels[$month])) {
                $values[] = $__labels[$month];
            } else {
                $values[] = 0;
            }
        }

        return [
            'labels' => $this->getCalendarLabels(),
            'series' => $values
        ];
        
    }

    private function getCalendarLabels()
    {
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return $labels;
    }

    /**
     * Get sales based on current year
     *
     * @param string $yearmonth This could be Year or Month. If month, format should be Y-m
     * 
     * @return array
     */
    private function getSalesByYear(string $yearmonth = null): array
    {
        $year = $yearmonth ?? date('Y');
        
        $sales = $this->orderModel->aggregate([
            [
                '$match' => [
                    
                    'created_at' => new \MongoDB\BSON\Regex($year),
                    'payment_status' => Order::CO
                ],
            ],
            [
                '$lookup' => [
                    'from' => 'order_catalogs',
                    'localField' => '_id',
                    'foreignField' => 'order_id',
                    'as' => 'order_items'
                ]
            ],
            [
                '$sort' => [
                    'created_at' => 1
                ]
            ]
        
        ])->toArray();

        return $sales;
    }

    /**
     * Get total sales of the current month
     *
     * @return int
     */
    public function getCurrentMonth()
    {
        $orders = $this->getSalesByYear(date('Y-m'));
        
        $grandTotal = 0;
        foreach($orders as $order){
            foreach($order->order_items as $item){
                $total = ($item->price * $item->quantity);
                $grandTotal += $total;
                unset($item);
            }
            unset($order);
        }

        return $grandTotal;
    }

    /**
     * Get most saleable product
     *
     * @return array
     */
    public function getMostSaleableCatalog(): array
    {
        $orderCatalog = new OrderCatalog();

        $_catalogs = [];

        $catalogs = $orderCatalog->find()->toArray();

        foreach($catalogs as $catalog){
            $order = new Order();
            $result = $order->findOne(['_id' => $catalog->order_id, 'payment_status' => Order::CO]);
            if ($result) {

                if (isset($_catalogs[$catalog->sku])) {
                    $_catalogs[$catalog->sku]['count'] += 1;
                    continue;
                }

                $_catalogs[$catalog->sku] = [
                    'sku' => $catalog->sku,
                    'catalog_name' => $catalog->catalog_name,
                    'count' => 1,
                ];

            }
            unset($catalog);
        }
        
        $data = [];
        $a = 0;
        foreach($_catalogs as $catalog){
            if ($a >= 5) {
                break;
            }
            if ($catalog['count'] >= 1) {
                $data[] = $catalog;
                $a++;
            }
            unset($catalog);
        }
        
        return $data;
    }

    /**
     * Get total of orders who actually paid(complete)
     * and those incomplete
     */
    public function getConvertedAndUnpaid()
    {
        $orders = $this->orderModel->getOrders(Order::CO);
        
        $container = [];
        for($b = 1; $b < 13; $b++){
            $y = date('Y-') . ($b < 10 ? '0' . $b : $b);
            $container['complete'][$y] = 0;
            $container['pending'][$y] = 0;
            $container['in_transit'][$y] = 0;
            $container['cancelled'][$y] = 0;
            $container['processing'][$y] = 0;
        }
        
        foreach($orders as $order){
            $status = strtolower(str_replace(' ', '_', $order->payment_status));
            $exploded = explode(' ', $order->created_at);

            $date = date('Y-m', strtotime(array_shift($exploded)));

            if (isset($container[$status][$date])) {
                $container[$status][$date] += count($order->order_items);
                continue;
            } else {
                $container[$status][$date] = count($order->order_items);
            }
            unset($order);
        }
        $_container = [];
        foreach($container as $status => $values){
            foreach($values as $value){
                $_container[$status][] = $value;
                unset($value);
            }
            unset($values);
        }
        
        return [
            'labels' => $this->getCalendarLabels(),
            'series' => $_container
        ];
    }

    /**
     * Get total sale
     *
     * @return int
     */
    private function getTotalSales()
    {
        $orders = $this->orderModel->aggregate([
            [
                '$match' => [
                    'payment_status' => Order::CO
                ]
            ],
            [
                '$lookup' => [
                    'from' => 'order_catalogs',
                    'localField' => '_id',
                    'foreignField' => 'order_id',
                    'as' => 'order_items'
                ]
            ]
        ])->toArray();
        
        $grandTotal = 0;
        
        foreach($orders as $order){
            foreach($order->order_items as $item){
                $total = ($item->price * $item->quantity);

                $grandTotal += $total;

                unset($item);
            }

            unset($order);
        }

        return $grandTotal;
    }

}