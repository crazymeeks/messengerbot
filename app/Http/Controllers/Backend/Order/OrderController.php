<?php

namespace App\Http\Controllers\Backend\Order;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\OrderRepository;

class OrderController extends Controller
{
    

    public function listing()
    {
        $view_data = [
            'page_title' => 'Orders',
            'recent_activities' => []
        ];
        return view('backend.pages.orders.listing', $view_data);
    }

    /**
     * Get catalog data and display to datatable
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataTable(Request $request, OrderRepository $orderRepository)
    {
        
        $orders = $orderRepository->setDataTableLimit($request->length)
                                      ->setDataTableOffset($request->start)
                                      ->setDataTableOrder($request->columns[$request->order[0]['column']]['data'], $request->order[0]['dir'])
                                      ->setDataTableSearch($request->search['value'])
                                      ->setRequest($request)
                                      ->getDataTableData();
        
        return $orders;
    }

    public function edit(Order $orderModel, string $id)
    {

        $order = $orderModel->aggregate([
            [
                '$match' => [
                    '_id' => new \MongoDB\BSON\ObjectId($id)
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
        
        $view_data = [
            'page_title' => 'Edit order',
            'order' => $order[0],
            'statuses' => $orderModel->getOrderStatuses(),
            'payment_statuses' => [
                Order::PE,
                Order::PR,
                Order::CA,
                Order::PA,
                Order::CO,
                Order::VO,

            ]
        ];
        return view('backend.pages.orders.edit', $view_data);
    }

    /**
     * Update order status
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            '_id' => 'required',
            'status' => 'required'
        ]);

        try{
            $id = new \MongoDB\BSON\ObjectId($request->_id);
            $updatable = $order->setId($id)
                  ->isUpdatableStatus($request->status);
            if (!$updatable) {
                throw \App\Exceptions\OrderStatusException::cannotUpdateOrderStatus();
            }
            
            $result = $order->updateOne([
                '_id' => $id,
            ], [
                '$set' => [
                    'status' => $request->status
                ]
            ]);

            return response()->json('Order status successfully updated!', 200);

        }catch(\App\Exceptions\OrderStatusException $e){
            return response()->json($e->getMessage(), 400);
        }catch(\Exception $e){
            
            return response()->json('Oops! Something went wrong while updating order status. Please try again', 400);
        }
    }

    /**
     * Update order status
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            '_id' => 'required',
            'payment_status' => 'required'
        ]);

        try{
            $id = new \MongoDB\BSON\ObjectId($request->_id);
            $updatable = $order->setId($id)
                  ->isUpdatableStatus($request->payment_status, 'payment_status');
            if (!$updatable) {
                throw \App\Exceptions\OrderStatusException::cannotUpdateOrderPaymentStatus();
            }
            
            $result = $order->updateOne([
                '_id' => $id,
            ], [
                '$set' => [
                    'payment_status' => $request->payment_status
                ]
            ]);

            return response()->json('Order payment status successfully updated!', 200);

        }catch(\App\Exceptions\OrderStatusException $e){
            return response()->json($e->getMessage(), 400);
        }catch(\Exception $e){
            
            return response()->json('Oops! Something went wrong while updating order payment status. Please try again', 400);
        }
    }
}
