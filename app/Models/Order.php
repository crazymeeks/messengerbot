<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class Order extends Model
{
    
    const PE = 'Pending';
    const PR = 'Processing';
    const IT = 'In Transit';
    const CA = 'Cancelled';
    const CO = 'Complete';

    const PA = 'Paid';
    const VO = 'Void';

    protected $collection = 'orders';

    protected $_id = null;

    protected $fillable = [
        'user_id', // could be user's facebook id
        'reference_number',
        'firstname',
        'lastname',
        'email',
        'mobile_number',
        'shipping_address',
        'status',
        'payment_method',
        'payment_status'
    ];

    /**
     * Set model's id
     *
     * @param \MongoDB\BSON\ObjectId $id
     * 
     * @return $this
     */
    public function setId(\MongoDB\BSON\ObjectId $id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * Check if status can be updatable
     * 
     * @param string $new_status
     * @param string $field
     * 
     * @return boolean
     */
    public function isUpdatableStatus(string $new_status, string $field = 'status')
    {
        if ($this->getId() === null) {
            throw new \Exception("Model's id is required! Set it by calling setId() method");
        }

        $result = $this->findOne(['_id' => $this->getId()]);

        if ($result) {
            if ($result->{$field} == self::CO) {
                return false;
            }


            if ($result->{$field} == self::IT && in_array($new_status, [self::CA, self::PE, self::PR])) {
                return false;
            } elseif ($result->{$field} == self::CA) {
                return false;
            }

            return true;
        }

        return true;
    }

    /**
     * Get order id
     *
     * @return \MongoDB\BSON\ObjectId|null
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Get order status
     *
     * @return array
     */
    public function getOrderStatuses()
    {
        return [
            self::PE,
            self::PR,
            self::IT,
            self::CA,
            self::CO
        ];
    }

    /**
     * Get order and its items based on payment status
     *
     * @param string $payment_status
     * 
     * @return array
     */
    public function getOrderByPaymentStatus(string $payment_status)
    {
        $allowed_status = array_merge($this->getOrderStatuses(), [self::VO, self::PA]);

        if (!in_array($payment_status, $allowed_status)) {
            throw new \Exception(sprintf("%s payment status is not allowed! Please choose from %s", $payment_status, implode(', ', $allowed_status)));
        }

        $orders = $this->aggregate([
            [
                '$match' => [
                    'payment_status' => $payment_status
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

        return $orders;

    }

    /**
     * Get all orders with their corresponding items
     *
     * @return array
     */
    public function getOrders()
    {
        $orders = $this->aggregate([
            
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

        return $orders;
    }
}
