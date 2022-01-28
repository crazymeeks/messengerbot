<?php

namespace Tests\DataProvider\Brand;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DataProvider
{


    public function getFormUploadedImage()
    {

        $_FILES = [
            'thumbnail' => [
                'name' => 'penguin.jpg',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__ . '/_files/penguin.jpg',
                'error'    => 0,
                'size'     => 20000,
            ]
        ];

        return new UploadedFile($_FILES['thumbnail']['tmp_name'], $_FILES['thumbnail']['name']);
    }

    /**
     * Update product image
     *
     * @return array
     */
    public function data()
    {
        $request = app(Request::class);

        $request->replace([
            'name' => 'San Miguel Flavored Beer',
            'description' => '<p>Test Description</p>',
            '__xtest' => true,
        ]);

        $request->files->set('thumbnail', $this->getFormUploadedImage());

        return [
            array($request)
        ];

    }
}