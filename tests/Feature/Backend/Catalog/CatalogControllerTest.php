<?php

namespace Tests\Feature\Backend\Catalog;

use App\Models\Catalog;

class CatalogControllerTest extends \Tests\TestCase
{

    /**
     * @dataProvider catalogProvider
     */
    public function testCreateCatalog(array $catalog)
    {

        session()->put(\App\Http\Controllers\Backend\Catalog\CatalogController::IMAGE_DIR, ['images/catalog/image.jpg']);

        $response = $this->json('POST', route('admin.catalog.post.create'), $catalog);

        $response->assertStatus(200);
        $catalog = new Catalog();
        $catalog = $catalog->findOne();
        $this->assertEquals('Pants', $catalog->name);
    }

    /**
     * @dataProvider catalogProvider
     */
    public function testUpdateCatalog(array $catalog)
    {
        $catalogModel = new Catalog();
        
        $insertResult = $catalogModel->insertOne($catalog);
        $catalog['_id'] = $insertResult->getInsertedId()->__toString();
        $catalog['name'] = 'Polo Shirt';
        $response = $this->json('POST', route('admin.catalog.post.create'), $catalog);

        $response->assertStatus(200);
        $result = $catalogModel->findOne();
        $this->assertEquals('Polo Shirt', $result->name);
    }

    /**
     * @dataProvider catalogProvider
     */
    public function testDeleteCatalog(array $catalog)
    {
        $catalogModel = new Catalog();
        $insertResult = $catalogModel->insertOne($catalog);
        $id = $insertResult->getInsertedId()->__toString();
        $response = $this->json('POST', route('admin.catalog.post.delete'), ['_id' => $id]);

        $response->assertStatus(200);
        $result = $catalogModel->findOne();
        $this->assertNull($result);
    }

    /**
     * Deactivate/activate catalog
     * @dataProvider catalogProvider
     */
    public function testToggleActivate(array $catalog)
    {
        $catalogModel = new Catalog();
        $insertResult = $catalogModel->insertOne($catalog);
        $id = $insertResult->getInsertedId()->__toString();
        $response = $this->json('POST', route('admin.catalog.post.toggle.activate'), ['_id' => $id]);
        
        $response->assertStatus(200);
        $result = $catalogModel->findOne();
        
        $this->assertEquals('Catalog successfully deactivated!', $response->original);
        $this->assertEquals('0', $result->status);
    }

    /**
     * @dataProvider dataTableRequest
     */
    public function testDisplayCatalogInDatatable(array $dt, array $data)
    {
        $catalogModel = new Catalog();
        $catalogModel->insertOne($data);
        $response = $this->json('GET', route('admin.catalog.datatable'), $dt);

        $this->assertEquals(1, $response->original['recordsTotal']);
    }

    public function catalogProvider()
    {
        $data = [
            'name' => 'Pants',
            'description' => 'Uniqlo pants for men',
            'sku' => 'unq-pmen',
            'price' => 20,
            'image_urls' => '/images/catalogs/38493843983943.jpg',
            'discount_price' => 0,
            'status' => Catalog::ACTIVE,
        ];

        return [
            array($data)
        ];
    }

    public function dataTableRequest()
    {
        $dt = [
            'draw' => 1,
            'columns' => [
                [
                    'data' => 'name',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'sku',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'price',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'discount_price',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'status',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => '_id',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],

            ],
            'order' => [
                [
                    'column' => '0',
                    'dir' => 'desc'
                ]
            ],
            'start' => '0',
            'length' => '10',
            'search' => [
                'value' => NULL,
                'regex' => 'false'
            ],
            '_' => '1600436890036',
        ];

        $data = [
            'name' => 'Pants',
            'description' => 'Uniqlo pants for men',
            'sku' => 'unq-pmen',
            'price' => 20,
            'image_urls' => '/images/catalogs/38493843983943.jpg',
            'discount_price' => 0,
            'status' => Catalog::ACTIVE,
        ];

        return [
            array($dt, $data)
        ];
    }
}