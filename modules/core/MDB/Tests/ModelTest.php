<?php

namespace MDB\Tests;

class ModelTest extends \Tests\TestCase
{


    public function testInsertToMongoDB()
    {
        
        $model = (new \MDB\Tests\SampleModel());
        
        $model->insertOne([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'name' => 'Admin User',
        ]);

        $collection = $model->find();
        $results = $collection->toArray();

        $this->assertEquals('admin', $results[0]['username']);

    }

    public function tearDown(): void
    {
        $model = (new \MDB\Tests\SampleModel());
        $model->deleteMany();

        parent::tearDown();
    }


}

class SampleModel extends \MDB\Model
{
    protected $collection = 'sample_collection';
}