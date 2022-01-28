<?php

namespace Tests\DataProvider\ProductUnit;

class DataProvider
{

    public function data()
    {
        $data = [
            'unit_type' => 'ml',
            'unit_value' => '320'
        ];

        return [
            array($data)
        ];
    }
}