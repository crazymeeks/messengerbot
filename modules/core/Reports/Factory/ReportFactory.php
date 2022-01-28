<?php

namespace Reports\Factory;

use Reports\ReportInterface;

class ReportFactory
{

    public static function make(string $namespace): \Reports\ReportInterface
    {
        $instance = app($namespace);

        if (!$instance instanceof \Reports\ReportInterface) {
            throw new \Exception(sprintf("The object %s must implement %s", get_class($instance), \Reports\ReportInterface::class));
        }

        return $instance;
    }
}