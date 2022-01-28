<?php

namespace Data;

class DataObject
{

    protected $container = [];

    public function __call($method, $args)
    {

        if (strncasecmp($method, 'set', 3) == 0) {
            $key = mb_substr($method, 3);
            list($value) = $args;
            $this->container[$key] = $value;
            return $this;
        }


        if (strncasecmp($method, 'get', 3) == 0) {
            $key = mb_substr($method, 3);
            
            return isset($this->container[$key]) ? $this->container[$key] : null;
        }

    }
}