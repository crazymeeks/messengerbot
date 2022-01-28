<?php

namespace Data\Tests\Unit;

use Data\DataObject;

class DataObjectTest extends \Tests\TestCase
{

    public function testSetDataToDataObject()
    {

        // Using magic call
        $dataObject = new DataObject();
        $dataObject->setConfigName('config_name');
        $data = $dataObject->getConfigName();
        $this->assertEquals('config_name', $data);


        
        
    }
}