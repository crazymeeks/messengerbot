<?php

namespace App\Exceptions;

class CatalogImageRequiredException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Catalog image is required!");
    }
}