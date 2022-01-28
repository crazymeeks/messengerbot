<?php

namespace App\Models\Data;

use App\Models\Api\DataInterface;

abstract class BaseData implements DataInterface
{

    const ID = 'id';

    protected $container = [];

    /**
     * Fields that should not be updated
     *
     * @var array
     */
    protected $guardedOnUpdate = [];

    public function setId(string $id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    protected function setData(string $key, $value)
    {
        $this->container[$key] = $value;

        return $this;
    }

    protected function getData(string $key)
    {
        return isset($this->container[$key]) ? $this->container[$key] : null;
    }
    

    /**
     * Set the fields that should not be updated when performing update
     *
     * @param array $fields
     * 
     * @return $this
     */
    public function setOnUpdateGuardField(array $fields)
    {
        $this->guardedOnUpdate = $fields;
        
        return $this;
    }

    /**
     * Get fields that should not be updated when performing update
     *
     * @return array
     */
    public function getOnUpdateGuardedField()
    {
        return $this->guardedOnUpdate;
    }

    /**
     * Remove guarded fields. Guarded fields will not
     * be inserted or modified
     *
     * @param array $fields An array of fields
     *        $fields = array(
     *             'name' => 'value'
     *        );
     * 
     * @return array
     */
    public function removeGuadedFields(array $fields)
    {

        $guardedFields = $this->getOnUpdateGuardedField();
        
        if (count($guardedFields) <= 0 ) {
            return $fields;
        }

        $_fields = [];
        foreach($fields as $key => $value){
            if (!in_array($key, $guardedFields)) {
                $_fields[$key] = $value;
            }

            unset($value, $key);
        }

        return $_fields;
    }

    /**
     * Dynamic method setter and getter
     *
     * @param string $name
     * @param array $args
     * 
     * @return mixed
     */
    public function __call($name, $args)
    {
        if (strncasecmp($name, 'set', 3) == 0) {
            $method = substr($name, 3);
            list($value) = $args;
            $this->container[$method] = $value;

            return $this;
        }

        if (strncasecmp($name, 'get', 3) == 0) {
            $method = substr($name, 3);
            if (isset($this->container[$method])) {
                return $this->container[$method];
            }

            return null;
        }

        throw new \BadMethodCallException("Method {$name}() does not exist in " . get_class($this));
    }
}