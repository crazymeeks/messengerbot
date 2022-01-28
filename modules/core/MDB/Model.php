<?php

namespace MDB;

use MongoDB\Client;

abstract class Model
{

    private $connected_collection = null;

    public function __construct()
    {
        $config = $this->getConfig();
        
        $con = new Client($config['uri'], $config['uriOptions'], $config['driverOptions']);
        
        $this->connectToCollection($con);
    }


    /**
     * Dynamic method call.
     * 
     * This will allow our custom models to directly call
     * method of \MongoDB\Client
     *
     * @param string $name
     * @param array $args
     * 
     * @return mixed
     */
    public function __call($name, $args)
    {
        
        $collection = $this->getConnectedCollection();
        $param1 = isset($args[0]) ? $args[0] : [];

        $param2 = isset($args[1]) ? $args[1] : [];
        $param3 = isset($args[2]) ? $args[2] : [];
        $param4 = isset($args[3]) ? $args[3] : [];
        $param5 = isset($args[4]) ? $args[4] : [];

        return $collection->{$name}($param1, $param2, $param3, $param4, $param5);
    }

    private function connectToCollection(Client $client)
    {
        $db = $this->getConfig()['database'];

        $database = $client->{$db};

        $collection = $database->{$this->getCollectionName()};

        $this->connected_collection = $collection;
    }

    private function getConnectedCollection()
    {
        return $this->connected_collection;
    }

    private function getCollectionName()
    {
        if (!property_exists($this, 'collection')) {
            throw new \Exception("Property 'collection' is required for model '" . get_class($this) . "'");
        }

        return $this->collection;
    }


    private function getConfig()
    {
        return config('mongodb');
    }
    

}